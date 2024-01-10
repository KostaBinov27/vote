<?php

/**
 * Displaying the data in the meta box
 *
 * @link       https://linkedin.com/in/kosta-binov
 * @since      1.0.0
 *
 * @package    Votes
 * @subpackage Votes/admin/partials
 */

$votes = new Votes_Public(true, true);
$data = array("post_id" => get_the_ID());
$percentages = $votes->get_percentage($data); ?>

<div class="votesWrap">
    <div class="votes">
        <p> <?php _e('Voted with `YES` - '); echo number_format($percentages['yes'], 1) . '%'; ?></p>
    </div>
    <div class="votes">
        <p><?php _e('Voted with `NO` - '); echo number_format($percentages['no'], 1) . '%';  ?></p>
    </div>
</div>