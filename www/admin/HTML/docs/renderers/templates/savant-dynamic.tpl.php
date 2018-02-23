<?=$this->form['javascript']?>
<form <?=$this->form['attributes']?>>
<?php if (!empty($this->form['hidden'])) : ?>
<div style="display:none;"><?=$this->form['hidden']?></div>
<?php endif; ?>
<table class="qfmaintable" cellpadding="0" cellspacing="0" border="0">
<?php foreach($this->form['sections'] as $section): ?>
<?php if (isset($section['header'])) : ?>
  <tr><td colspan="2" class="qfheader"><?php echo $section['header'] ?></td></tr>
<?php endif; ?>
<?php foreach ($section['elements'] as $element) : ?>
  <tr>
<?php if (!empty($element['style'])) : ?>   
   <?php if ($element['style'] === 'buttons' && $element['type'] === 'group') : ?>
    <td colspan="2" class="qfbuttons"><?php foreach ($element['elements'] as $groupElement): ?><?=$groupElement['html']?><?=$groupElement['separator']?><?php endforeach; ?></td>
   <?php elseif ($element['style'] === 'table' && $element['type'] === 'group') : ?>
    <td class="qflabel"><?php if ($element['required']): ?><span class="qfrequired">*</span><?php endif; ?><label for="<?php echo !empty($element['id']) ? $element['id'] : $element['name']; ?>"><?=$element['label'] ?></label></td>
    <td class="qfelement"><?php if (!empty($element['error'])) : ?><div class="error"><?=$element['error']?></div><?php endif; ?>
      <table cellpadding="2" cellspacing="0" border="0">
      <tr><?php foreach ($element['elements'] as $groupElement): ?><td><?=$groupElement['html']?><br />
      <?php if (!empty($groupElement['id'])) : ?><label for="<?=$groupElement['id']?>"><?=$groupElement['label']?></label><?php else : ?><?=$groupElement['label']?><?php endif; ?><?php if ($groupElement['required']): ?><span class="qfrequired">*</span><?php endif; ?></td>
<?php endforeach; ?>
      </tr></table></td>
   <?php elseif ($element['style'] === 'section') : ?>
    <td colspan="2" class="qfsection"><?=$element['label']?><?=$element['html']?></td>
   <?php endif; ?>
<?php elseif ($element['type'] === 'group') : ?>
    <td class="qflabel"><?php if ($element['required']): ?><span class="qfrequired">*</span><?php endif; ?><label for="<?php echo !empty($element['id']) ? $element['id'] : $element['name']; ?>"><?=$element['label'] ?></label></td>
    <td class="qfelement"><?php if (!empty($element['error'])) : ?><div class="error"><?=$element['error']?></div><?php endif; ?>
<?php foreach ($element['elements'] as $groupElement): ?><?=$groupElement['html']?><?=$groupElement['separator']?>
<?php endforeach; ?>
    </td>
<?php else : ?>
    <td class="qflabel"><?php if ($element['required']): ?><span class="qfrequired">*</span><?php endif; ?><?php if (!empty($element['id'])) : ?><label for="<?=$element['id']?>"><?=$element['label'] ?></label><?php else : ?><?=$element['label'] ?><?php endif; ?></td>
    <td class="qfelement"><?php if (!empty($element['error'])) : ?><div class="error"><?=$element['error']?></div><?php endif; ?><?=$element['html']?></td>
<?php endif; ?>
  </tr>
<?php endforeach; ?>
<?php endforeach; ?>
<?php if (!empty($this->form['requirednote'])) : ?>
  <tr><td colspan="2"><?=$this->form['requirednote']?></td></tr>
<?php endif; ?>
</table>
</form>
