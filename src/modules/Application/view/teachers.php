<?php
/**
 * @var $model array
 */
?>
<h1>Teachers</h1>
<ul>
    <?php foreach ($model['data'] as $teacher): ?>
        <li>
            <ul>
                <li><span>Teacher name <?= $teacher['teacher_name']?></span></li>
                <li><span>Teacher title <?= $teacher['teacher_title']?></span></li>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>

