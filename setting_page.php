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

		if ( isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce']) ) {
			// 不管结果变没变，有提交则直接以提交的数据 wpwatermark_options
			update_option('wpwatermark_options', $wpwatermark_options);
			?>
            <div style="font-size: 25px;color: red; margin-top: 20px;font-weight: bold;"><p>老部落轻水印插件设置保存完毕!!!</p></div>
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
        table {
            border-collapse: collapse;
        }

        table, td, th {border: 1px solid #cccccc;padding:5px;}
        .buttoncss {background-color: #4CAF50;
            border: none;cursor:pointer;
            color: white;
            padding: 15px 22px;
            text-align: center;
            text-decoration: none;
            display: inline-block;border-radius: 5px;
            font-size: 12px;font-weight: bold;
        }
        .buttoncss:hover {
            background-color: #008CBA;
            color: white;
        }
        input,select{border: 1px solid #ccc; padding: 5px 0px; border-radius: 3px; padding-left:5px;}
        #leixingtable{border: 0px; width:100%;height:100%;}
    </style>

    <div style="margin:5px;">
        <h2>老部落轻水印插件 - WPWaterMark</h2>
        <hr />
        <p>老部落轻水印插件，目前网上少有的实现九宫格、随机九宫格、满铺水印效果的WordPress水印插件，可以根据站长实际需要实现图片水印、采集防盗效果。</p>
        <p>插件网站： <a href="https://www.laobuluo.com" target="_blank">老部落</a> / <a href="https://www.laobuluo.com/2770.html" target="_blank">轻水印插件发布地址</a> / 站长交流QQ群： <a href="https://jq.qq.com/?_wv=1027&k=5gBE7Pt" target="_blank"> <font color="red">594467847</font></a>（宗旨：多做事，少说话，效率至上）</p>
        <form action="<?php echo wp_nonce_url('./admin.php?page=' . WPWaterMark_INDEXFILE); ?>" name="wpwatermarkform" method="post">
            <table width="800" height="395">
                <tr>
                    <td width="180" style="text-align:right;"> <b>水印类型：</b></td>
                    <td width="620">
                        <table width="362" height="135" border="0" id="leixingtable">
                            <tr>
                                <td>
                                    <label>
                                        <input type="radio"  name="watermark_type" value="text_watermark" <?php if($wpwatermark_options['watermark_type'] == 'text_watermark') { echo 'checked="checked"'; } ?> />
                                        <strong>本文水印</strong>
                                        <br />

                                        文本内容：
                                        <input name="text_content" type="text" id="textfield" value="<?php echo esc_attr($wpwatermark_options['text_content']); ?>" size="30" />
                                        <br />
                                        文本字体：
                                        <select id='text_font' name="text_font" required>
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
                                        <br />
                                        文本倾斜：
                                        <input name="text_angle" type="text" value="<?php echo esc_attr($wpwatermark_options['text_angle']); ?>" size="20" /> °  （默认是水平，可以设置倾斜度）<br />

                                        文本大小：
                                        <input name="text_size" type="text" id="textfield2" value="<?php echo esc_attr($wpwatermark_options['text_size']); ?>" size="20" /> px<br />
                                        文本颜色：

                                    </label>

                                    <label for="label"></label>
                                    <input name="text_color" type="text" value="<?php echo esc_attr($wpwatermark_options['text_color']); ?>" size="15"/>
                                    <span id="color_code"></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input type="radio" name="watermark_type" value="image_watermark" <?php if($wpwatermark_options['watermark_type'] == 'image_watermark') { echo 'checked="checked"'; } ?> />
                                        <strong>图片水印</strong>
                                        <br />
                                        <input type="text" name="watermark_mark_image" value="<?php echo esc_attr($wpwatermark_options['watermark_mark_image']); ?>" size="80"/>
                                        <p>说明：自己准备一个水印图片URL地址（比如:https://www.laobuluo.com/watermark.png），最好是透明 .png 图片。</p>
                                    </label>
                                </td>
                            </tr>
                        </table>
                        <p>&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;"><b>水印位置：</b></td>
                    <td>
                        <p>
                            <label>
                                <input type="radio" name="watermarkPosition" value="jiugongge" <?php if ($wpwatermark_options['watermark_position'] > 0 and $wpwatermark_options['watermark_position'] < 10) { echo 'checked="checked"'; } ?> /> 九宫格位置
                            </label>
                            <label for="label2"></label>
                            <input name="jiugongge_value" type="text" id="label2" value=<?php if ($wpwatermark_options['watermark_position'] > 0 and $wpwatermark_options['watermark_position'] < 10) { echo '"' . esc_attr($wpwatermark_options['watermark_position']) . '"'; } else { echo '"" disabled'; } ?> size="10" /> (1-9 固定位置）<br />
                            <label>
                                <input type="radio" name="watermarkPosition" value="suiji" <?php if ($wpwatermark_options['watermark_position'] == 0) { echo 'checked="checked"'; } ?>/> 随机九宫格（每次水印位置随机）
                            </label><br />
                            <label>
                                <input type="radio" name="watermarkPosition" value="manpu" <?php if ($wpwatermark_options['watermark_position'] == 10) { echo 'checked="checked"'; } ?>/> 满铺水印效果（超强防盗图片）
                            </label><br />
                            <input type="hidden" name="watermark_position" value="<?php echo esc_attr($wpwatermark_options['watermark_position']); ?>" />
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;"><b>其他设置：</b></td>
                    <td>
                        <p>
                            平铺水印间距
                            <label for="label4"></label>
                            <input name="watermark_margin" type="text" id="label4" value="<?php echo esc_attr($wpwatermark_options['watermark_margin']); ?>" size="20" /> （平铺时水印之间距离）
                        </p>
                        <p>
                            图片水印透明度
                            <label for="label4"></label>
                            <input name="watermark_diaphaneity" type="text" id="label4" value="<?php echo esc_attr($wpwatermark_options['watermark_diaphaneity']); ?>" size="20" /> （0-100数值 数值越小越透明）
                        </p>
                        <p>
                            水印边距
                            <label for="label3"></label>
                            <input name="watermark_spacing" type="text" id="label3" value="<?php echo esc_attr($wpwatermark_options['watermark_spacing']); ?>" size="20" /> px  （水印起始位置距离图片四周边距数值，建议30）
                        </p>
                    </td>

                <tr>
                    <th>                    </th>
                    <td>
                        <input type="submit" name="submit" value="保存轻水印插件设置" class="buttoncss" />
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;"><b>演示效果：</b></td>
                    <td>
                        <p>
                            <input type="button" id="preview" value="点击刷新预览水印效果" />
                            <label id="preview_block"></label>
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
        </form>
        <p> 说明：如果您喜欢我们的插件，如果您有插件相关问题，均可加入我们社群QQ： <a href="https://jq.qq.com/?_wv=1027&k=5gBE7Pt" target="_blank"> <font color="red">594467847</font></a>（2000+ 站长交流）或者关注微信公众号：imweber</p>
    </div>

	<?php
}
?>