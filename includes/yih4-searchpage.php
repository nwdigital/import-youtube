<?php
/*
* YouTube Search List Page
*/
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

function yih4_nwd_search_results(){
  $options3 = get_option( 'yih4_tab_settings_3' );
    if(!empty($options3['yih4_tab_option_18'])){
      $video_slug = $options3['yih4_tab_option_18'];
    } else { $video_slug = 'video'; };

  ?>
  <div class="row-fluid">
      <main id="content" role="main" class="span12">
          <!-- Begin Content -->
          <div id="hyv-page-container" style="clear:both;">
              <div class="hyv-content-alignment">
                    <!-- Searchbar Section start -->

                    <div id="yih4-admin-message"></div>
                      <!-- Results Section start -->
                      <div id="hyv-watch-content" class="postbox yih4-section hyv-watch-main-col">
                            <input type="hidden" id="pageToken" value="">
                            <div class="btn-group yt-next-prev" role="group" aria-label="..." style="display:none;">
                              <button type="button" id="pageTokenPrev" value="" class="button button-secondary">Prev</button>
                              <button type="button" id="pageTokenNext" value="" class="button button-secondary">Next</button>
                              <span class="yih4spinner" style="display: inline-block;" id="yih4RSenterkey"></span>
                              <span class="ytceckall" style="margin-left:15px;float:right;"><input type="checkbox" class="chk_boxes chk_boxes1" label="check all"  /></input></span>
                              <input type="button" style="float:right;" class="button button-primary" id="ajax_post" value="Import Videos">
                              <br/><br/>
                            </div>

                          <ul id="hyv-watch-related" class="hyv-video-list">
                          </ul>
                          <div>
                              <div >
                                <br/>
                                <span class="ytceckall" style="margin-left:15px;float:right;"><input type="checkbox" class="chk_boxes chk_boxes1" label="check all"  /></input></span>
                                <input type="button" style="float:right;" class="button button-primary" id="ajax_post3" value="Import Videos">
                                <span class="yih4spinner" id="spinner_bottom" style="display:inline-block;padding-right:10px;float:right;"></span>
                              </div>
                          </div>
                      </div>
                      <!-- Ads width 300px holder start -->
                      <div id="hyv-watch-sidebar-ads" style="max-width:400px;">
                           <div id="hyv-watch-channel-brand-div" class="yih4-section postbox">
                              <style>
                              .donate-button {
                                display: inline-block;
                                width: 100%;
                                border: none;
                                background-color: #4CAF50;
                                color: white;
                                padding: 14px 0;
                                font-size: 16px;
                                cursor: pointer;
                                text-align: center;
                              }
                              .donate-button:hover {
                                background-color: #ddd;
                                color: black;
                              }
                              </style>
                              <h2 style="font-size:1.4em;padding-left:0;">Support Future Development!</h2>
                              <p>Your generous donation will help support future plugin development which can include new features, regular updates and enhancements! -<i>Thank You!</i></p>
                              <p><i>Northwoods Digital - <a href="https://northwoodsdigital.com">https://northwoodsdigital.com</a></i></p>
                              <p><a class="donate-button" href="https://northwoodsdigital.com/plugins/import-youtube/#donate" target="_blank">Donate Now</a></p>
                              <p><a style="text-transform:capitalize;" href="<?php echo esc_url(site_url('/'.$video_slug.'s'));?>"><u>View <?php echo sanitize_text_field($video_slug); ?>s</u></a></p>
                          </div>
                      </div>
                      <!-- Ads width 300px holder end -->
                  </div>
          </div>
      </main>
  </div>
  <?php
}
