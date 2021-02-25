<?php
/**
 * @var $model array
 */

?>
<h1>Classes</h1>

<?php if (!empty($model['error_messages'])): ?>
    <h2>Validation messages</h2>
    <ul>
        <?php foreach ($model['error_messages'] as $field_name => $error_messages): ?>
            <span><?= implode('<br/>', $error_messages)?></span>
            <br/>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<ul>
    <?php foreach ($model['data'] as $classes):?>
        <li>
            <ul>
                <li><span>Class title: <?= $classes['teacher_title']?></span></li>
                <li><span>Teacher name <?= $classes['teacher_name']?></span></li>
                <li><span>Teacher title <?= $classes['teacher_title']?></span></li>
                <?php if (isset($classes['schedules'])): ?>
                    <?php $classes['schedules'] = json_decode($classes['schedules'], true); ?>
                    <strong>Class schedules</strong>
                    <ul>
                        <?php foreach ($classes['schedules'] as $data): ?>
                            <li> <?= ucfirst($data ['week_day']) ?>:
                                From <?= ucfirst($data ['start_time']) ?>
                                To <?= ucfirst($data ['end_time']) ?> </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </ul>
            <form method="post">
                <input type="hidden" value="<?= $classes['class_id']?>" name="class_id" />
                <select name="week_name" required>
                    <option>Select week day</option>
                    <option value="monday">Monday</option>
                    <option value="tuesday">Tuesday</option>
                    <option value="wednesday">Wednesday</option>
                    <option value="thursday">Thursday</option>
                    <option value="friday">Friday</option>
                    <option value="saturday">Saturday</option>
                </select>
                <br/ >
                <span>Start time</span>
                <input type="number" max="24" min="00" name="start_time_hour" required />
                <input type="number" max="60" min="00" name="start_time_minutes" required />
                <br>
                <span>End time</span>
                <input type="number" max="24" min="00" name="end_time_hour" required />
                <input type="number" max="60" min="00" name="end_time_minutes" required />
                <br>
                <button type="submit">Save schedule</button>

            </form>
        </li>
    <?php endforeach; ?>
</ul>







