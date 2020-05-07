<?php
/**
 *  插件设置页面
 */
// require_once('WaterMarkFunctions.php');

function wpwatermark_setting_page() {
// 如果当前用户权限不足
	if (!current_user_can('manage_options')) {
		wp_die('Insufficient privileges!');
	}

	$wpwatermark_options = get_option('wpwatermark_options');
	if ($wpwatermark_options && !empty($_POST)) {
		$wpwatermark_options['watermark_type'] = (isset($_POST['watermark_type'])) ? sanitize_text_field(trim(stripslashes($_POST['watermark_type']))) : $wpwatermark_options['watermark_type'];
		$wpwatermark_options['text_content'] = (isset($_POST['text_content'])) ? sanitize_text_field(trim(stripslashes($_POST['text_content']))) : $wpwatermark_options['text_content'];
		$wpwatermark_options['text_font'] = (isset($_POST['text_font'])) ? sanitize_text_field(trim(stripslashes($_POST['text_font']))) : $wpwatermark_options['text_font'];
		$wpwatermark_options['text_angle'] = (isset($_POST['text_angle'])) ? sanitize_text_field(trim(stripslashes($_POST['text_angle']))) : $wpwatermark_options['text_angle'];
		$wpwatermark_options['text_size'] = (isset($_POST['text_size'])) ? sanitize_text_field(trim(stripslashes($_POST['text_size']))) : $wpwatermark_options['text_size'];
		$wpwatermark_options['text_color'] = (isset($_POST['text_color'])) ? sanitize_text_field(trim(stripslashes($_POST['text_color']))) : $wpwatermark_options['text_color'];
		$wpwatermark_options['watermark_mark_image'] = (isset($_POST['watermark_mark_image'])) ? sanitize_text_field(trim(stripslashes($_POST['watermark_mark_image']))) : $wpwatermark_options['watermark_mark_image'];
		$wpwatermark_options['watermark_position'] = (isset($_POST['watermark_position'])) ? sanitize_text_field(trim(stripslashes($_POST['watermark_position']))) : $wpwatermark_options['watermark_position'];
		$wpwatermark_options['watermark_margin'] = (isset($_POST['watermark_margin'])) ? sanitize_text_field(trim(stripslashes($_POST['watermark_margin']))) : $wpwatermark_options['watermark_margin'];
		$wpwatermark_options['watermark_diaphaneity'] = (isset($_POST['watermark_diaphaneity'])) ? sanitize_text_field(trim(stripslashes($_POST['watermark_diaphaneity']))) : $wpwatermark_options['watermark_diaphaneity'];
		$wpwatermark_options['watermark_spacing'] = (isset($_POST['watermark_spacing'])) ? sanitize_text_field(trim(stripslashes($_POST['watermark_spacing']))) : $wpwatermark_options['watermark_spacing'];
		$wpwatermark_options['watermark_min_width'] = (isset($_POST['watermark_min_width'])) ? sanitize_text_field(trim(stripslashes($_POST['watermark_min_width']))) : $wpwatermark_options['watermark_min_width'];
		$wpwatermark_options['watermark_min_height'] = (isset($_POST['watermark_min_height'])) ? sanitize_text_field(trim(stripslashes($_POST['watermark_min_height']))) : $wpwatermark_options['watermark_min_height'];

		if ( isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce']) ) {
			// 不管结果变没变，有提交则直接以提交的数据 wpwatermark_options
			update_option('wpwatermark_options', $wpwatermark_options);
			?>
            <div class="notice notice-success settings-error is-dismissible"><p><strong>设置已保存。</strong></p></div>
			<?php
		}
		if ( isset($_POST['preview_wpnonce']) && wp_verify_nonce($_POST['preview_wpnonce']) ) {
			$demo_img_path = plugin_dir_path( __FILE__ );
			$im_url = $demo_img_path . 'demo.jpg';
			$new_im_url = $demo_img_path . 'preview.jpg';
			if ( $wpwatermark_options['watermark_type'] === 'text_watermark' ) {
				wpWaterMarkCreateWordsWatermark(
					$im_url,
					$new_im_url,
					$wpwatermark_options['text_content'],
					$wpwatermark_options['watermark_spacing'],
					$wpwatermark_options['text_size'],
					$wpwatermark_options['text_color'],
					$wpwatermark_options['watermark_position'],
					$wpwatermark_options['text_font'],
					$wpwatermark_options['text_angle'],
					$wpwatermark_options['watermark_margin']
				);
			} elseif ( $wpwatermark_options['watermark_type'] === 'image_watermark' ) {
				wpWaterMarkCreateImageWatermark(
					$im_url,
					$wpwatermark_options['watermark_mark_image'],
					$new_im_url,
					$wpwatermark_options['watermark_position'],
					$wpwatermark_options['watermark_diaphaneity'],
					$wpwatermark_options['watermark_spacing'],
					$wpwatermark_options['watermark_margin']
				);
			}
		}
	}
	?>

    <style>
         .wrap-widefat th,.wrap-widefat td {border-bottom: 1px solid #cccccc;}
		 .wrap-widefat th{border-right:1px solid #cccccc ;}
		 .wrap-widefat th{width:12%;min-width: 80px;}
		 .wrap-widefat tr:nth-child(even){background:#f9f9f9;}
		 .txt-lieb{ border-bottom: 1px solid #ccc; margin-top: 5px;padding-bottom: 5px;}
		 .txt-lieb .ipn-win{min-width:228px;}
		 .txt-assist{color: #999 !important;}
		 .teli-assist{display: inline-block;min-width: 100px;}
    </style>

    <div class="wrap">
    <h1 class="wp-heading-inline">老部落轻水印插件 - WPWaterMark</h1> <a href="https://www.laobuluo.com/2770.html" target="_blank"class="page-title-action">插件介绍</a>
        <hr class="wp-header-end">      
        <p>老部落轻水印插件，目前网上少有的实现九宫格、随机九宫格、满铺水印效果的WordPress水印插件，可以根据站长实际需要实现图片水印、采集防盗效果。</p>
        <p>插件网站： <a href="https://www.laobuluo.com" target="_blank">老部落</a> / <a href="https://www.laobuluo.com/2113.html" target="_blank"><font color="red">新人建站常用的虚拟主机/云服务器</font></a> / 站长QQ群： <a href="https://jq.qq.com/?_wv=1027&k=5IpUNWK" target="_blank"> <font color="red">1012423279</font></a>（交流建站和运营） / 公众号：QQ69377078（插件反馈）</p>
        <form action="<?php echo wp_nonce_url('./admin.php?page=' . WPWaterMark_INDEXFILE); ?>" name="wpwatermarkform" method="post">
		    <div id="menu-locations-wrap">
				 <table class="widefat wrap-widefat">
					    <tr>
							<th scope="row">水印类型</th>
							<td>
								<div class="txt-lieb">
									<p>
										<input type="radio"  name="watermark_type" value="text_watermark" <?php if($wpwatermark_options['watermark_type'] == 'text_watermark') { echo 'checked="checked"'; } ?> />
                                        <strong>本文水印</strong>
									</p>
									<p>
										文本内容：
										<input class="ipn-win" name="text_content" type="text" id="textfield" value="<?php echo esc_attr($wpwatermark_options['text_content']); ?>" size="30" />
									</p>
									<p>
										文本字体：
										<select class="ipn-win" id='text_font' name="text_font" required>
											<?php
											$dir    = plugin_dir_path( __FILE__ ) . 'fonts/';
											$files1 = scandir($dir);
											foreach ($files1 as $k=>$v) {
												if ( $v != "." && $v != ".." ) {
													$is_selected = $wpwatermark_options['text_font'] == $v ? "selected" : "";
													echo "<option value='$v' $is_selected>$v</option>";
												}
											}
											?>
										</select>
									</p>
									<p>
										文本倾斜：
										<input class="ipn-win" name="text_angle" type="text" value="<?php echo esc_attr($wpwatermark_options['text_angle']); ?>" size="20" /> <span class="txt-assist">（默认是水平，可以设置倾斜度）</span>
									</p>
									<p>
										文本大小：
										<input class="ipn-win" name="text_size" type="text" id="textfield2" value="<?php echo esc_attr($wpwatermark_options['text_size']); ?>" size="20" /> px
									</p>
									<p> 
									    文本颜色：
										<input class="ipn-win" name="text_color" type="text" value="<?php echo esc_attr($wpwatermark_options['text_color']); ?>" size="15"/>
										<span id="color_code"></span>
									</p>
								</div>
								<div style="margin-top: 20px;">
									<p>
										<input type="radio" name="watermark_type" value="image_watermark" <?php if($wpwatermark_options['watermark_type'] == 'image_watermark') { echo 'checked="checked"'; } ?> />
                                        <strong>图片水印</strong>
									</p>
									<p>
										 <input type="text" name="watermark_mark_image" value="<?php echo esc_attr($wpwatermark_options['watermark_mark_image']); ?>" size="80"/>
									</p>
									<p class="txt-assist">准备水印图片URL（比如:https://www.laobuluo.com/watermark.png）透明 .png格式，建议（220*80px）。</p>
								</div>
							</td>
						</tr>
						<tr>
							<th scope="row"><b>水印位置</b></th>
							<td>
								<p> <input type="radio" name="watermarkPosition" value="jiugongge" <?php if ($wpwatermark_options['watermark_position'] > 0 and $wpwatermark_options['watermark_position'] < 10) { echo 'checked="checked"'; } ?> /> 九宫格位置</p>
							    <p> <input name="jiugongge_value" type="text" id="label2" value=<?php if ($wpwatermark_options['watermark_position'] > 0 and $wpwatermark_options['watermark_position'] < 10) { echo '"' . esc_attr($wpwatermark_options['watermark_position']) . '"'; } else { echo '"" disabled'; } ?> size="10" /> (1-9 固定位置）</p>
							    <p> <input type="radio" name="watermarkPosition" value="suiji" <?php if ($wpwatermark_options['watermark_position'] == 0) { echo 'checked="checked"'; } ?>/> 随机九宫格（每次水印位置随机）</p>
							    <p> <input type="radio" name="watermarkPosition" value="manpu" <?php if ($wpwatermark_options['watermark_position'] == 10) { echo 'checked="checked"'; } ?>/> 满铺水印效果（超强防盗图片）</p>
							    <p> <input type="hidden" name="watermark_position" value="<?php echo esc_attr($wpwatermark_options['watermark_position']); ?>" /></p>
							</td>
						</tr>
						<tr>
							<th scope="row">其他设置</th>
							<td>
							    <p>
							        <span class="teli-assist">平铺水印间距:</span>
							        <input name="watermark_margin" type="text"  value="<?php echo esc_attr($wpwatermark_options['watermark_margin']); ?>" size="20" /> <span class="txt-assist">（平铺时水印之间距离）</span>
							    </p>
							    <p>
							        <span class="teli-assist">图片水印透明度:</span>
							        <input name="watermark_diaphaneity" type="text" id="label4" value="<?php echo esc_attr($wpwatermark_options['watermark_diaphaneity']); ?>" size="20" /> <span class="txt-assist">（0-100数值 数值越小越透明）</span>
							    </p>
							    <p>
							        <span class="teli-assist">水印边距:</span>
							        <input name="watermark_spacing" type="text" id="label3" value="<?php echo esc_attr($wpwatermark_options['watermark_spacing']); ?>" size="20" /> px   <span class="txt-assist">（水印起始位置距离图片四周边距数值，建议30）</span>
							    </p>
							    <hr>
							    <p>
							        <span class="teli-assist">加水印条件:</span></br></br>
							        宽度：<input name="watermark_min_width" type="text" value="<?php echo esc_attr($wpwatermark_options['watermark_min_width']); ?>" size="10" />px /  高度：<input name="watermark_min_height" type="text" value="<?php echo esc_attr($wpwatermark_options['watermark_min_height']); ?>" size="10" />px <span class="txt-assist">（自定义图片宽和高超过多少像素才加水印）</span>
							    </p>
							</td>
						</tr>
						<tr>
						    <th scope="row">保存设置</th>
						    <td style="padding: 20px 10px;">
						        <input type="submit" name="submit" value="保存设置" class="button button-primary"/>
						    </td>
						</tr>
						<tr>
						    <th scope="row">预览效果</td>
						    <td  style="padding: 20px 10px;">
								
						        <p><input type="button" id="preview" value="预览效果" class="button" /></p>
								
						        <p style="margin-top:5px;"><label id="preview_block"></label></p>  
						      
						            <script type="text/javascript">
						                (function ($) {
						                    function readyFn() {
						                        function randomNum(minNum,maxNum){
						                            switch(arguments.length){
						                                case 1:
						                                    return parseInt(Math.random()*minNum+1,10);
						                                    break;
						                                case 2:
						                                    return parseInt(Math.random()*(maxNum-minNum+1)+minNum,10);
						                                    break;
						                                default:
						                                    return 0;
						                                    break;
						                            }
						                        }
						
						                        $('input[type="color"]').colorpicker({hoverChange:true});
						                        $('input[name="text_color"]').colorpicker({
						                            'onSelect':function(color){
						                                $('#color_code').text('（颜色修改为：'+color+'）');
						                            }
						                        });
						
						                        $('input[value="jiugongge"]').click(function () {
						                            $('input[name="watermark_position"]').val($('input[name="jiugongge_value"]').val());
						                            $('input[name="jiugongge_value"]').attr("disabled", false);
						                        });
						                        $('input[name="jiugongge_value"]').change(function(){
						                            $('input[name="watermark_position"]').val($('input[name="jiugongge_value"]').val());
						                        });
						                        $('input[value="suiji"]').click(function(){
						                            $('input[name="watermark_position"]').val('0');
						                            $('input[name="jiugongge_value"]').attr('disabled', true);
						                        });
						                        $('input[value="manpu"]').click(function(){
						                            $('input[name="watermark_position"]').val('10');
						                            $('input[name="jiugongge_value"]').attr('disabled', true);
						                        });
						
						                        $('#preview').click(function () {
						                            let watermark_type;
						                            if ( $('input[value="text_watermark"]').is(':checked') ) {
						                                watermark_type = "text_watermark";
						                            }
						                            if ( $('input[value="image_watermark"]').is(':checked') ) {
						                                watermark_type = "image_watermark";
						                            }
						                            let text_content = $('input[name="text_content"]').val();
						                            let text_font = $('#text_font option:selected').val();
						                            let text_angle = $('input[name="text_angle"]').val();
						                            let text_size = $('input[name="text_size"]').val();
						                            let text_color = $('input[name="text_color"]').val();
						                            let watermark_mark_image = $('input[name="watermark_mark_image"]').val();
						                            let watermark_position = $('input[name="watermark_position"]').val();
						                            let watermark_margin = $('input[name="watermark_margin"]').val();
						                            let watermark_diaphaneity = $('input[name="watermark_diaphaneity"]').val();
						                            let watermark_spacing = $('input[name="watermark_spacing"]').val();
						                            $.post(
						                                "<?php echo './admin.php?page=' . WPWaterMark_INDEXFILE; ?>",
						                                {
						                                    'preview_wpnonce': "<?php echo wp_create_nonce(); ?>",
						                                    'watermark_type': watermark_type,
						                                    'text_content': text_content,
						                                    'text_font': text_font,
						                                    'text_angle': text_angle,
						                                    'text_size': text_size,
						                                    'text_color': text_color,
						                                    'watermark_mark_image': watermark_mark_image,
						                                    'watermark_position': watermark_position,
						                                    'watermark_margin': watermark_margin,
						                                    'watermark_diaphaneity': watermark_diaphaneity,
						                                    'watermark_spacing': watermark_spacing,
						                                },
						                                function( res ) {
						                                    // if ( res['status'] ==1 ) {
						                                    let x = res;
						                                    let img_src = "<?php echo plugins_url('preview.jpg', __FILE__) . '?' ?>" + randomNum(0, 99999);
						                                    let img_code = "<img src='" + img_src + "' />";
						                                    $('#preview_block').html(img_code);
						                                },
						                            );
						                        });
						                    }
						                    $(document).ready(readyFn);
						                })(jQuery);
						            </script>
						        </p>
						    </td>
						</tr>
				 </table>
			</div>
        </form>

        <hr>
        <div style='text-align:center;line-height: 50px;'>
            <a href="https://www.laobuluo.com/" target="_blank">插件主页</a> | <a href="https://www.laobuluo.com/2770.html" target="_blank">插件发布页面</a> | <a href="https://jq.qq.com/?_wv=1027&k=5IpUNWK" target="_blank">QQ群：1012423279</a> | 公众号：QQ69377078（插件反馈）

        </div>
       
    </div>

	<?php
}
?>