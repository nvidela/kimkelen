{<?php $nb1 = count($careers); $j = 0; foreach ($careers as $key => $value): ++$j ?>
"<?php echo $key ?>": <?php echo json_encode($value->asArray()).($nb1 == $j ? '' : ',') ?>
<?php endforeach; ?>
}
