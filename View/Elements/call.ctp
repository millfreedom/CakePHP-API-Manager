<div class="call" data-id="<?php echo $call['id']; ?>">
    <a name="<?php echo $call['id']; ?>_anchor"></a>
    <h2><?php echo $call['name']; ?></h2> 
    <p><?php echo $call['description']; ?></p>
    <button type="button" data-url="<?php echo $call['url']; ?>" data-tokens='<?php echo !empty($call['tokens']) ? json_encode($call['tokens']) : ''; ?>' id="<?php echo $call['id']; ?>"><?php echo $call['button']; ?></button>
    <?php if (!empty($call['data'])) : ?>
    <br />
    <textarea name="text"><?php echo json_encode($call['data'], JSON_PRETTY_PRINT); ?></textarea>
    <?php endif; ?>
</div>