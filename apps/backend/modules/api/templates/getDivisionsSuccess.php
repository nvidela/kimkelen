{<?php $nb1 = count($items); $j = 0; foreach ($items as $key => $value): ++$j ?>
"<?php echo $key ?>": <?php echo json_encode($value->asArray()).($nb1 == $j ? '' : ',') ?>
<?php endforeach; ?>
}
