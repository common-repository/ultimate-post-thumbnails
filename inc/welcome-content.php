<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$image_path = '2.1'; ?>

<div class="wrap about-wrap themenow-about-wrap">
    <h1>Welcome to Ultimate Post Thumbnails <?php echo UPT_VERSION; ?></h1>
    <p class="about-text">Thank you for updating to the latest version. Ultimate Post Thumbnails <?php echo UPT_VERSION; ?> introduce Visual Composer integration, PhotoSwipe lightbox and Smart Image Size to make your post thumbnail even better!</p>
    <div class="upt-badge">Version <?php echo UPT_VERSION; ?></div>
    <h2 class="nav-tab-wrapper wp-clearfix">
		<a href="upt-about.php" class="nav-tab nav-tab-active">What’s New</a>
	</h2>
    <div class="changelog point-releases">
		<h3>New Features Release</h3>
		<p><strong>Version <?php echo UPT_VERSION; ?></strong> involves a lot of work and changes to bring you those awesome new features. For more information, see <a href="http://www.themenow.com/plugins/ultimate-post-thumbnails/examples">examples</a>.</p>
	</div>
    <div class="headline-feature feature-video">
        <img width="1050" height="689" src="http://themenow.net/upt/images/<?php echo $image_path; ?>/visual-composer-integration.jpg" alt="">
    </div>
    <hr>
    <div class="custom-header-link feature-section one-col">
        <h2>PhotoSwipe Lightbox</h2>
        <p>Native HTML5 full-screen/Touch gestures/Smart lazy-loading/Responsive images support/Social sharing/Keyboard access/Semantic and SEO friendly markup</p>
        <img width="1050" height="506" src="http://themenow.net/upt/images/<?php echo $image_path; ?>/photoswipe-lightbox.jpg" alt="">
        <hr>
        <h2>Smart Image Size</h2>
        <p>Change image ratio without even touching the image, finish your work in seconds!</p>
        <img width="1050" height="299" src="http://themenow.net/upt/images/<?php echo $image_path; ?>/custom-image-ratio.png" alt="">
        <hr>
        <div class="changelog">
            <h2>Other Changes</h2>
            <div class="under-the-hood three-col">
                <div class="col">
                    <h3><code class="tn-change">Change</code> One Set Thumbnails Setting</h3>
                    <p>Multiple featured images setting are unified, no need to repeat settings on all images any more.</p>
                </div>
                <div class="col">
                    <h3><code class="tn-new">New</code> Image Size in Lightbox</h3>
                    <p>Added the option to control the image size that should be shown in the lightbox.</p>
                </div>
                <div class="col">
                    <h3><code class="tn-change">Change</code> Thumbnail Slider Width</h3>
                    <p>Thumbnail slider respects the width of its container now, images will be stretched to fit the width if smaller.</p>
                </div>
            </div>
        </div>
        <hr>
    </div>
</div>
