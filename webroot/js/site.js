/**
 * JSONSH - JSON Syntax Highlight
 * 
 * @version v0.2.0 ( 4/20/2012 )
 *
 * @author <a href="http://www.peterschmalfeldt.com">Peter Schmalfeldt</a>
 *
 * @namespace jsonsh 
 */
var jsonsh = {
	
	/** Track whether we've done an initial reformatting */
	is_pretty: false,

	/** Store old Source to compare if changes were made before formatting again */
	old_value: '',

    /** Store old api url to compare if changes were made before formatting again */
	old_url: '',

	/** Single place to update how fast/slow the animations will take */
	animation_speed: 250,

	/** Single place to update how you want to manage the tabbing in your reformatting */
	tab_space: '  ',
	
	/** Initialize JSONSH */
    init: function()
    {
        /** Look for changes to JSON source */
        jQuery('#source').change(function()
        {
            /** Only process JSON if there is some, and it is not the same as before */
            if(jQuery(this).val() != jsonsh.old_value)
            {
                /** Passed out initial tests, go ahead and make it pretty */
                jsonsh.make_pretty();
                
                /** Update our old value to the latest and greatest */
                jsonsh.old_value = jQuery(this).val();
            }
            /** Source is blank now, no need to do anything, so reset interface */
            else if(jQuery(this).val() == '')
            {
                jsonsh.reset_interface();
            }
        });
    },
	
	/**  */
	make_pretty: function(json)
	{
		/** Clear out the old HTML from the output first */
		jQuery('#output_wrapper').html('');

		/** Try to Validate & Format Source */
		if(jQuery('#source').val() != '' || json != '')
		{

			try
			{
				/** Parse JSON using Awesome JSON Lint */
				if(typeof json != 'undefined')
					var result = jsonlint.parse(json);
				else
					var result = jsonlint.parse(jQuery('#source').val());
				
				/** Check for a result */
				if(result)
				{
					/** Stick out decompresses JSON string into the output wrapper so we can make it all pretty */
					jQuery('#output_wrapper').html('<pre class="prettyprint linenums"><code class="language-js">' + JSON.stringify(result, null, jsonsh.tab_space) + '</code></pre>');
					
					/** Check if we've already made the JSON pretty, if not let's also update the original source ( doing it again just makes the UX horrible ) */
					if(jsonsh.is_pretty === false)
					{
						/** Update formatting of original source so user can copy / paste if they want */
						jQuery('#source').val(JSON.stringify(result, null, jsonsh.tab_space)).scrollTop(0);
						
						/** Track that we updated the users JSON source formatting */
						jsonsh.is_pretty = true;
					}
					
					/** Get rid of any existing result messages */
					jQuery('#result').fadeOut(jsonsh.animation_speed);
					
					/** Now that the heavy lifing is done, MAKE IT PRETTY */
					prettyPrint();
					
					/** Allow the user to click to highlight a row to keep mental track of things better */
					jQuery('li').click(function(){
						jQuery(this).toggleClass('select');
						
						//For code folding
						var startLen = $(this).find('span.pln').first().html().length,
						next = $(this).next('li'),
						nextLen = next.find('span.pln').first().html().length;
						
						function getNext(oldNext){
							next = $(oldNext).next('li');
							nextLen = next.find('span.pln').first().html().length;
						}
						
						if(next.is(":visible")){
							while(startLen < nextLen){
								next.hide();
								getNext(next);
							}
						}else{
							while(!next.is(":visible")){
								next.show();
								getNext(next);
							}
						}
						
					});
					
					/** Bring up the reset link to allow user to get back to the begging ASAP */
					jQuery('.reset').fadeIn(jsonsh.animation_speed);
				}
			}
			/** Invalid Source */
			catch(error)
			{
				/** Bring up the reset link to allow user to get back to the begging ASAP */
				jQuery('.reset').fadeIn(jsonsh.animation_speed);
				
				/** Show the error message we got to the user so then know what's up */
				jQuery('#result').addClass('fail').html(error.message).fadeIn();
			}
		}
		/** Source code is blank now, so reset the interface */
		else
		{
			jsonsh.reset_interface();
		}
	},
	
	/** Put everything back the way it was when we first started... easy peasy */
	reset_interface: function()
	{
		jQuery('#result').fadeOut(jsonsh.animation_speed);
		jQuery('#source').val('');
		jQuery('#output_wrapper').html('');

		jsonsh.is_pretty = false;
		jsonsh.old_value = '';
		jsonsh.old_url = '';		
	}
}

/** Initialize JSONSH */
jQuery(function()
{
	jsonsh.init();	
});

/** --- THIRD PARTY LIBRARIES --- */

/** JSON2 Third Party Plugin - https://github.com/zaach/jsonlint */
if(!this.JSON){this.JSON={};}(function(){function f(n){return n < 10 ? '0'+n:n;}if(typeof Date.prototype.toJSON !== 'function'){Date.prototype.toJSON=function(key){return isFinite(this.valueOf())?this.getUTCFullYear()+'-'+f(this.getUTCMonth()+1)+'-'+f(this.getUTCDate())+'T'+f(this.getUTCHours())+':'+f(this.getUTCMinutes())+':'+f(this.getUTCSeconds())+'Z':null;};String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(key){return this.valueOf();};}var cx=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,escapable=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,gap,indent,meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'},rep;function quote(string){escapable.lastIndex=0;return escapable.test(string)?'"'+string.replace(escapable,function(a){var c=meta[a];return typeof c === 'string' ? c :'\\u'+('0000'+a.charCodeAt(0).toString(16)).slice(-4);})+'"':'"'+string+'"';}function str(key,holder){var i,k,v,length,mind=gap,partial,value=holder[key];if(value && typeof value==='object' && typeof value.toJSON==='function'){value=value.toJSON(key);}if(typeof rep==='function'){value=rep.call(holder,key,value);}switch(typeof value){case 'string':return quote(value);case 'number':return isFinite(value) ? String(value):'null';case 'boolean':case 'null':return String(value);case 'object':if(!value){return 'null';}gap += indent;partial=[];if(Object.prototype.toString.apply(value)==='[object Array]'){length=value.length;for(i=0;i<length;i+=1){partial[i]=str(i,value)||'null';}v=partial.length===0?'[]':gap?'[\n'+gap+partial.join(',\n'+gap)+'\n'+mind+']':'['+partial.join(',')+']';gap=mind;return v;}if(rep && typeof rep==='object'){length=rep.length;for(i=0;i<length;i+=1){k=rep[i];if(typeof k==='string'){v=str(k,value);if(v){partial.push(quote(k)+(gap ? ': ':':')+v);}}}}else{for(k in value){if(Object.hasOwnProperty.call(value,k)){v=str(k,value);if(v){partial.push(quote(k)+(gap ? ': ':':')+v);}}}}v=partial.length===0 ? '{}':gap ? '{\n'+gap+partial.join(',\n'+gap)+'\n'+mind+'}':'{'+partial.join(',')+'}';gap=mind;return v;}}if(typeof JSON.stringify!=='function'){JSON.stringify=function(value,replacer,space){var i;gap='';indent='';if(typeof space==='number'){for(i=0;i<space;i+=1){indent+=' ';}}else if(typeof space==='string'){indent=space;}rep=replacer;if(replacer && typeof replacer!=='function' && (typeof replacer!=='object' || typeof replacer.length!=='number')){throw new Error('JSON.stringify');}return str('',{'':value});};}if(typeof JSON.parse!=='function'){JSON.parse=function(text,reviver){var j;function walk(holder,key){var k,v,value=holder[key];if(value && typeof value === 'object'){for(k in value){if(Object.hasOwnProperty.call(value,k)){v=walk(value,k);if(v!==undefined){value[k]=v;}else{delete value[k];}}}}return reviver.call(holder, key, value);}cx.lastIndex=0;if(cx.test(text)){text=text.replace(cx,function(a){return '\\u'+('0000'+a.charCodeAt(0).toString(16)).slice(-4);});}if(/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,''))){j=eval('('+text+')');return typeof reviver==='function'?walk({'': j},''):j;}throw new SyntaxError('JSON.parse');};}}());

/** JSON Lint Third Party Plugin - https://github.com/zaach/jsonlint */
var jsonlint=function(){var a=!0,b=!1,c={},d=function(){var a={trace:function(){},yy:{},symbols_:{error:2,JSONString:3,STRING:4,JSONNumber:5,NUMBER:6,JSONNullLiteral:7,NULL:8,JSONBooleanLiteral:9,TRUE:10,FALSE:11,JSONText:12,JSONValue:13,EOF:14,JSONObject:15,JSONArray:16,"{":17,"}":18,JSONMemberList:19,JSONMember:20,":":21,",":22,"[":23,"]":24,JSONElementList:25,$accept:0,$end:1},terminals_:{2:"error",4:"STRING",6:"NUMBER",8:"NULL",10:"TRUE",11:"FALSE",14:"EOF",17:"{",18:"}",21:":",22:",",23:"[",24:"]"},productions_:[0,[3,1],[5,1],[7,1],[9,1],[9,1],[12,2],[13,1],[13,1],[13,1],[13,1],[13,1],[13,1],[15,2],[15,3],[20,3],[19,1],[19,3],[16,2],[16,3],[25,1],[25,3]],performAction:function(a,b,c,d,e,f,g){var h=f.length-1;switch(e){case 1:this.$=a;break;case 2:this.$=Number(a);break;case 3:this.$=null;break;case 4:this.$=!0;break;case 5:this.$=!1;break;case 6:return this.$=f[h-1];case 13:this.$={};break;case 14:this.$=f[h-1];break;case 15:this.$=[f[h-2],f[h]];break;case 16:this.$={},this.$[f[h][0]]=f[h][1];break;case 17:this.$=f[h-2],f[h-2][f[h][0]]=f[h][1];break;case 18:this.$=[];break;case 19:this.$=f[h-1];break;case 20:this.$=[f[h]];break;case 21:this.$=f[h-2],f[h-2].push(f[h])}},table:[{3:5,4:[1,12],5:6,6:[1,13],7:3,8:[1,9],9:4,10:[1,10],11:[1,11],12:1,13:2,15:7,16:8,17:[1,14],23:[1,15]},{1:[3]},{14:[1,16]},{14:[2,7],18:[2,7],22:[2,7],24:[2,7]},{14:[2,8],18:[2,8],22:[2,8],24:[2,8]},{14:[2,9],18:[2,9],22:[2,9],24:[2,9]},{14:[2,10],18:[2,10],22:[2,10],24:[2,10]},{14:[2,11],18:[2,11],22:[2,11],24:[2,11]},{14:[2,12],18:[2,12],22:[2,12],24:[2,12]},{14:[2,3],18:[2,3],22:[2,3],24:[2,3]},{14:[2,4],18:[2,4],22:[2,4],24:[2,4]},{14:[2,5],18:[2,5],22:[2,5],24:[2,5]},{14:[2,1],18:[2,1],21:[2,1],22:[2,1],24:[2,1]},{14:[2,2],18:[2,2],22:[2,2],24:[2,2]},{3:20,4:[1,12],18:[1,17],19:18,20:19},{3:5,4:[1,12],5:6,6:[1,13],7:3,8:[1,9],9:4,10:[1,10],11:[1,11],13:23,15:7,16:8,17:[1,14],23:[1,15],24:[1,21],25:22},{1:[2,6]},{14:[2,13],18:[2,13],22:[2,13],24:[2,13]},{18:[1,24],22:[1,25]},{18:[2,16],22:[2,16]},{21:[1,26]},{14:[2,18],18:[2,18],22:[2,18],24:[2,18]},{22:[1,28],24:[1,27]},{22:[2,20],24:[2,20]},{14:[2,14],18:[2,14],22:[2,14],24:[2,14]},{3:20,4:[1,12],20:29},{3:5,4:[1,12],5:6,6:[1,13],7:3,8:[1,9],9:4,10:[1,10],11:[1,11],13:30,15:7,16:8,17:[1,14],23:[1,15]},{14:[2,19],18:[2,19],22:[2,19],24:[2,19]},{3:5,4:[1,12],5:6,6:[1,13],7:3,8:[1,9],9:4,10:[1,10],11:[1,11],13:31,15:7,16:8,17:[1,14],23:[1,15]},{18:[2,17],22:[2,17]},{18:[2,15],22:[2,15]},{22:[2,21],24:[2,21]}],defaultActions:{16:[2,6]},parseError:function(a,b){throw new Error(a)},parse:function(a){function o(){var a;a=b.lexer.lex()||1,typeof a!="number"&&(a=b.symbols_[a]||a);return a}function n(a){c.length=c.length-2*a,d.length=d.length-a,e.length=e.length-a}var b=this,c=[0],d=[null],e=[],f=this.table,g="",h=0,i=0,j=0,k=2,l=1;this.lexer.setInput(a),this.lexer.yy=this.yy,this.yy.lexer=this.lexer,typeof this.lexer.yylloc=="undefined"&&(this.lexer.yylloc={});var m=this.lexer.yylloc;e.push(m),typeof this.yy.parseError=="function"&&(this.parseError=this.yy.parseError);var p,q,r,s,t,u,v={},w,x,y,z;for(;;){r=c[c.length-1],this.defaultActions[r]?s=this.defaultActions[r]:(p==null&&(p=o()),s=f[r]&&f[r][p]);if(typeof s=="undefined"||!s.length||!s[0]){if(!j){z=[];for(w in f[r])this.terminals_[w]&&w>2&&z.push("'"+this.terminals_[w]+"'");var A="";this.lexer.showPosition?A="Parse error on line "+(h+1)+":\n"+this.lexer.showPosition()+"\nExpecting "+z.join(", "):A="Parse error on line "+(h+1)+": Unexpected "+(p==1?"end of input":"'"+(this.terminals_[p]||p)+"'"),this.parseError(A,{text:this.lexer.match,token:this.terminals_[p]||p,line:this.lexer.yylineno,loc:m,expected:z})}if(j==3){if(p==l)throw new Error(A||"Parsing halted.");i=this.lexer.yyleng,g=this.lexer.yytext,h=this.lexer.yylineno,m=this.lexer.yylloc,p=o()}for(;;){if(k.toString()in f[r])break;if(r==0)throw new Error(A||"Parsing halted.");n(1),r=c[c.length-1]}q=p,p=k,r=c[c.length-1],s=f[r]&&f[r][k],j=3}if(s[0]instanceof Array&&s.length>1)throw new Error("Parse Error: multiple actions possible at state: "+r+", token: "+p);switch(s[0]){case 1:c.push(p),d.push(this.lexer.yytext),e.push(this.lexer.yylloc),c.push(s[1]),p=null,q?(p=q,q=null):(i=this.lexer.yyleng,g=this.lexer.yytext,h=this.lexer.yylineno,m=this.lexer.yylloc,j>0&&j--);break;case 2:x=this.productions_[s[1]][1],v.$=d[d.length-x],v._$={first_line:e[e.length-(x||1)].first_line,last_line:e[e.length-1].last_line,first_column:e[e.length-(x||1)].first_column,last_column:e[e.length-1].last_column},u=this.performAction.call(v,g,i,h,this.yy,s[1],d,e);if(typeof u!="undefined")return u;x&&(c=c.slice(0,-1*x*2),d=d.slice(0,-1*x),e=e.slice(0,-1*x)),c.push(this.productions_[s[1]][0]),d.push(v.$),e.push(v._$),y=f[c[c.length-2]][c[c.length-1]],c.push(y);break;case 3:return!0}}return!0}},f=function(){var a={EOF:1,parseError:function(a,b){if(this.yy.parseError)this.yy.parseError(a,b);else throw new Error(a)},setInput:function(a){this._input=a,this._more=this._less=this.done=!1,this.yylineno=this.yyleng=0,this.yytext=this.matched=this.match="",this.conditionStack=["INITIAL"],this.yylloc={first_line:1,first_column:0,last_line:1,last_column:0};return this},input:function(){var a=this._input[0];this.yytext+=a,this.yyleng++,this.match+=a,this.matched+=a;var b=a.match(/\n/);b&&this.yylineno++,this._input=this._input.slice(1);return a},unput:function(a){this._input=a+this._input;return this},more:function(){this._more=!0;return this},pastInput:function(){var a=this.matched.substr(0,this.matched.length-this.match.length);return(a.length>20?"...":"")+a.substr(-20).replace(/\n/g,"")},upcomingInput:function(){var a=this.match;a.length<20&&(a+=this._input.substr(0,20-a.length));return(a.substr(0,20)+(a.length>20?"...":"")).replace(/\n/g,"")},showPosition:function(){var a=this.pastInput(),b=Array(a.length+1).join("-");return a+this.upcomingInput()+"\n"+b+"^"},next:function(){if(this.done)return this.EOF;this._input||(this.done=!0);var a,b,c,d;this._more||(this.yytext="",this.match="");var e=this._currentRules();for(var f=0;f<e.length;f++){b=this._input.match(this.rules[e[f]]);if(b){d=b[0].match(/\n.*/g),d&&(this.yylineno+=d.length),this.yylloc={first_line:this.yylloc.last_line,last_line:this.yylineno+1,first_column:this.yylloc.last_column,last_column:d?d[d.length-1].length-1:this.yylloc.last_column+b[0].length},this.yytext+=b[0],this.match+=b[0],this.matches=b,this.yyleng=this.yytext.length,this._more=!1,this._input=this._input.slice(b[0].length),this.matched+=b[0],a=this.performAction.call(this,this.yy,this,e[f],this.conditionStack[this.conditionStack.length-1]);if(a)return a;return}}if(this._input==="")return this.EOF;this.parseError("Lexical error on line "+(this.yylineno+1)+". Unrecognized text.\n"+this.showPosition(),{text:"",token:null,line:this.yylineno})},lex:function(){var a=this.next();return typeof a!="undefined"?a:this.lex()},begin:function(a){this.conditionStack.push(a)},popState:function(){return this.conditionStack.pop()},_currentRules:function(){return this.conditions[this.conditionStack[this.conditionStack.length-1]].rules}};a.performAction=function(a,b,c,d){var e=d;switch(c){case 0:break;case 1:return 6;case 2:b.yytext=b.yytext.substr(1,b.yyleng-2);return 4;case 3:return 17;case 4:return 18;case 5:return 23;case 6:return 24;case 7:return 22;case 8:return 21;case 9:return 10;case 10:return 11;case 11:return 8;case 12:return 14;case 13:return"INVALID"}},a.rules=[/^\s+/,/^-?([0-9]|[1-9][0-9]+)(\.[0-9]+)?([eE][-+]?[0-9]+)?\b/,/^"(\\["bfnrt/\\]|\\u[a-fA-F0-9]{4}|[^\0-\x09\x0a-\x1f"\\])*"/,/^\{/,/^\}/,/^\[/,/^\]/,/^,/,/^:/,/^true\b/,/^false\b/,/^null\b/,/^$/,/^./],a.conditions={INITIAL:{rules:[0,1,2,3,4,5,6,7,8,9,10,11,12,13],inclusive:!0}};return a}();a.lexer=f;return a}();typeof a!="undefined"&&typeof c!="undefined"&&(c.parser=d,c.parse=function(){return d.parse.apply(d,arguments)},c.main=function(b){if(!b[1])throw new Error("Usage: "+b[0]+" FILE");if(typeof process!="undefined")var d=a("fs").readFileSync(a("path").join(process.cwd(),b[1]),"utf8");else var e=a("file").path(a("file").cwd()),d=e.join(b[1]).read({charset:"utf-8"});return c.parser.parse(d)},typeof b!="undefined"&&a.main===b&&c.main(typeof process!="undefined"?process.argv.slice(1):a("system").args));return c}();