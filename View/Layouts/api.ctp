<link href='//fonts.googleapis.com/css?family=Anonymous+Pro' rel='stylesheet' type='text/css'>
<link href='/api_manager/css/style.css' rel='stylesheet' type='text/css'>

<div class="calls">
    <h1><?php echo $title; ?></h1>
    <div class="actions">
        <ul>
            <li>
                <input type="checkbox" id="showInfo" checked="checked" /> Show extended info
            </li>
            <li>
                <input type="checkbox" id="showData" checked="checked" /> Show data
            </li>
        </ul>
    </div>
    <?php foreach ($calls as $call) : ?>
    <div class="call">
        <h2><?php echo $call['name']; ?></h2> 
        <p><?php echo $call['description']; ?></p>
        <button type="button" data-url="<?php echo $call['url']; ?>" id="<?php echo $call['id']; ?>"><?php echo $call['button']; ?></button>
        <?php if (!empty($call['data'])) : ?>
        <br />
        <textarea name="text"><?php echo json_encode($call['data'], JSON_PRETTY_PRINT); ?></textarea>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
<div class="results">
    <h1>Results</h1>
    <div id="result"></div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.js"></script>
<script>
    $(document).ready(function() {
        $('#showInfo').click(function() {
            $(".call > p").toggle();
        });

        $('#showData').click(function() {
            $(".call > textarea").toggle();
        });

        $('button').click(function() {
            var call = $(this).parent();
            var ajax = {
                'url': $(this).attr('data-url'),
                'type': 'POST',
                'complete': function(xhr, res) { 
                    replyData = $.parseJSON(xhr.responseText);
                    
                    $("#result").html(
                        '<pre class="prettyprint linenums"><code class="language-js">' +
                        JSON.stringify(replyData, null, 2) + 
                        '</code></pre>'
                    );
                    
                    if (
                        (typeof replyData.status != 'undefined' && replyData.status == 'success') ||
                        typeof replyData.status == 'undefined'
                    ) {
                        $("button[data-url='"+this.url+"']").siblings('h2').append(' <span class="green">✔</span>');
                    } else {
                        $("button[data-url='"+this.url+"']").siblings('h2').append(' <span class="red">✘</span>');
                    }
                    
                    prettyPrint();
                }            
            }

            if (typeof call.find('textarea').val() != 'undefined') {
                ajax.data = JSON.parse(call.find('textarea').val());
            }

            $.ajax(ajax);
        });
    });
</script>