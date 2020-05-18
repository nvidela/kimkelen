{<?php $nb1 = count($students); $j = 0; foreach ($students as $key => $value): ++$j ?>
"<?php echo $key ?>": <?php echo json_encode($value->asArray()).($nb1 == $j ? '' : ',') ?>
<?php endforeach; ?>
}
