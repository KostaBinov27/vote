<?php

/**
 * Displaying the box for voting
 *
 * @link       https://linkedin.com/in/kosta-binov
 * @since      1.0.0
 *
 * @package    Votes
 * @subpackage Votes/public/partials
 */

$votes = new Votes_Public(true, true); 
$data = $votes->detect_if_voted(get_the_ID()); ?>

<div class="voting-wrap" id="vote_postID" post-id='<?php echo(get_the_ID()); ?>' sec="<?php echo wp_create_nonce("user_vote_nonce"); ?>" address="<?php echo esc_attr($votes->getIPAddress()); ?>">
    <div class="grid gap-50 grid-mob">
        <div class="text-wrap">
            <h6><?php echo ($data['voted']) ? 'THANK YOU FOR YOUR FEEDBACK.' : 'WAS THIS ARTICLE HELPFUL?';?></h6>
        </div>
        <div class="buttons-wrap grid gap-20">
            <button id="yes" class="button-yes grid gap-2 vote-buttons <?php echo ($data['voted_value'] === 'yes') ? 'clicked' : ''; ?>" <?php echo ($data['disabled'] == 1) ? 'disabled' : ''; ?>>
                <img src="<?php echo plugin_dir_url( __DIR__ ) ?>img/happy.svg">
                <span class="btn-yes-text"><?php echo ($data['voted']) ? number_format($data['percentages']['yes'], 1)."%" : _e('YES'); ?></span>
            </button>
            <button id="no" class="button-no grid gap-2 vote-buttons <?php echo ($data['voted_value'] === 'no') ? 'clicked' : ''; ?>" <?php echo ($data['disabled'] == 1) ? 'disabled' : ''; ?>>
                <img src="<?php echo plugin_dir_url( __DIR__ ) ?>img/sad.svg">
                <span class="btn-no-text"><?php echo ($data['voted']) ? number_format($data['percentages']['no'], 1)."%" : _e('NO'); ?></span>
            </button>
        </div>
    </div>
</div>