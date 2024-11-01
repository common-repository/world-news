<?php

class WorldNews extends WP_Widget{
    public function __construct()
    {
        parent::__construct('WorldNews', 'World news', array(
            'description' => 'Show news from world famous news portal.'
        ));
    }
    public function widget($args,$instance)
    {
        $check_dynamic_link = '';
        if (isset($instance['author']) == null ||
            isset($instance['publishedAt']) == null ||
//            isset($instance['content_title']) == null ||
            isset($instance['urlToImage']) == null ||
            isset($instance['content_description']) == null ||
            isset($instance['url']) == null
        )
        {
            if (is_user_logged_in()) {
                $link_to_widget=esc_url(home_url()) . '/wp-admin/widgets.php';
                 $check_dynamic_link .= "<a href='".$link_to_widget."' class='goto_widget'>Go to widget page</a>";
            } else {
                $check_dynamic_link .= ' ';
            }
            echo "<div class='check_dynamic_link'>".$check_dynamic_link."</div>";
        } else {
            $arr1 = array_values($instance['author']);
            $arr2 = array_values($instance['publishedAt']);
//            $arr3 = array_values($instance['content_title']);
            $arr4 = array_values($instance['urlToImage']);
            $arr5 = array_values($instance['content_description']);
            $arr6 = array_values($instance['url']);
            $main_content = '';
            $content_title = $instance['title'];
            $main_content .= $args['before_widget'] . $args['before_title'] . "<div class='world_news_title'>".$content_title ."</div>". $args['after_title'];

            for ($ar = 0; $ar < count($arr1); $ar++) {
                $main_content .= "<div class='single singleBlock$ar'>";
                if ($arr1[$ar] == "No author found") {
                    $main_content .= "<div class='author_block_not_found'><span>Author :<a href='$arr6[$ar]' target='_blank'>$arr1[$ar]</a></span></div>";
                } else {
                    $main_content .= "<div class='author_block'><span>Author :<a href='$arr6[$ar]' target='_blank'>$arr1[$ar]</a></span></div>";
                }
                $main_content .= "<div class='publish_block'><span>Published date :</span><a href='$arr6[$ar]' target='_blank'>$arr2[$ar]</a></div>";
//                $main_content .= "<div class='content_title'><label>Content title :<a href='$arr6[$ar]' target='_blank'>$arr3[$ar]</a></label></div>";
                if ($arr4[$ar] == "null") {
                    $main_content .= "<div class='content_image_null'><a href='$arr6[$ar]' target='_blank'><img src='$arr4[$ar]' alt='content image'></a></div>";

                } else {
                    $main_content .= "<div class='content_image'><a href='$arr6[$ar]' target='_blank'><img src='$arr4[$ar]' alt='content image'></a></div>";
                }
                $main_content .= "<div class='content_description'><a href='$arr6[$ar]' target='_blank'>$arr5[$ar]</a></div>";
                $main_content .= "</div>";
            }
            $main_content .= $args['before_widget'];
            echo $main_content;
        }
    }
    public function form($instance)
    {
        $title='';
        if(isset($instance['title'])){
            $title.=$instance['title'];
        }else{
            $title.=" ";
        }
        ?>

        <div class="dynamicContentBlocktitle">
            <div id="<?php echo $this->get_field_id('showCheckedSite');?>" class="showCheckedSite"></div>
            <div id="<?php echo $this->get_field_id('showCheckedSiteSave');?>"></div>
            <div id="<?php echo $this->get_field_id('CheckedSiteSaveLinkView');?>">
                <?php
                $checkedSite=$instance['checkedItem'];
                if($checkedSite != null){
                    echo "<b class='valueArray'>Selected websites are :</b>"."<br/>";
                    $uniqueArray=array_unique($checkedSite);
                    foreach ($uniqueArray as $valueArray){
                        echo "<div class='valueArray'>".$valueArray."</div>";
                    }
                }
                ?>
            </div>
            <div id="<?php echo $this->get_field_id('showContent');?>"></div>
            <p>
                <label for="<?php echo $this->get_field_id('title')?>">
                    Title :
                </label>
            </p>
            <p>
                <input type="text" class="widefat" name="<?php echo $this->get_field_name('title')?>" id="<?php echo $this->get_field_id('title')?>" value="<?php echo $title?>"/>
            </p>
        </div>
        <p class="message_category">Please Save the title</p>
        <div class="category_block">
            <p>
                <label for="<?php echo $this->get_field_id('news_category_id')?>">
                    News category :
                </label>
            </p>
            <p>
                <select class="widefat widgetAddedClass" name="<?php echo $this->get_field_name('news_category')?>"  id="<?php echo $this->get_field_id('news_category_id')?>" >
                    <option>-- Choose an option --</option>
                    <option value="technology">Technology</option>
                    <option value="weather">Weather</option>
                    <option value="sport">Sports</option>
                    <option value="health">Health</option>
                    <option value="politics">Politics</option>
                    <option value="entertainment">Entertainment</option>
                </select>
            </p>
            <div id="<?php echo $this->get_field_id('showSource');?>"></div>
            <div id="<?php echo $this->get_field_id('showFromDatabase');?>"></div>
            <div class="showSource"></div>
            <div class="showFromDatabase"></div>

        </div>
        <div class="dynamicContentBlocktitleOption">
            <script type="text/javascript">
                var news_category_value;
                var news_category_value_widget_added;
                (function ($) {
                    $(document).on('widget-added', function (event, widget) {
                        $(".category_block").css('display', 'none');
                        $(".message_category").css('display', 'block');
                        var widget_id = $(widget).attr('id');
                        if (widget_id) {
                            $(".widgetAddedClass").removeClass(widget_id + "_addedClass");
                            $(".widgetAddedClass").addClass(widget_id + "_addedClass");
                        }
                        if (widget_id + "_addedClass") {
//                        alert("."+widget_id+"_addedClass");
                            $("." + widget_id + "_addedClass").on('change', function () {
                                news_category_value_widget_added = $(this).val();
//                            alert(news_category_value_widget_added);
                                $("." + widget_id + "_addedClass option:selected").attr("selected", "selected");
                                var api_link_widget_added = "https://newsapi.org/v2/everything?q=" + news_category_value_widget_added + "&apiKey=7002deb905924b91b4841b5f4fc8e8ae";
//                              alert(api_link_widget_added);
                                $.getJSON(api_link_widget_added, function (data_widget_added) {
                                var value_widget_add = data_widget_added.articles;
//                                console.log(value_widget_add);
                                var result_widget_add = [];
                                var temp_arr_widget_add = [];
                                for (var w = 0; w < data_widget_added.articles.length; w++) {
                                    result_widget_add.push(data_widget_added.articles[w].source.name);
                                }
//                                console.log(result_widget_add);
                                    for (var d = 0; d < result_widget_add.length; d++) {
                                        if ($.inArray(result_widget_add[d], temp_arr_widget_add) == -1) {
                                            temp_arr_widget_add.push(result_widget_add[d]);
                                            if (news_category_value_widget_added !== '') {
                                                $(".showSource").css('display', 'block');
                                                $(".showSource").append(
                                                    "<div class='sources_class'><input type='checkbox' class='input_source_class' value='" + result_widget_add[d] + "'/>" + result_widget_add[d] + "</div>"
                                                );
                                            } else {
                                                $(".showSource").css('display', 'none');
                                            }
                                        }
                                    }
                                    $("input[type=checkbox]").change(function () {
                                        if ($(this).is(":checked")) {
                                            var checked_value_widget_added = $(this).val();
                                            for (var g = 0; g < value_widget_add.length; g++) {
                                                if (checked_value_widget_added == value[g].source.name) {

                                                    if (value_widget_add[g].author == null) {
                                                        value_widget_add[g].author = "No author found";
                                                    }
                                                    if (value_widget_add[g].urlToImage == null || value_widget_add[g].urlToImage == "content image") {
                                                    }
                                                    $(".showFromDatabase").append(
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('checkListItem');?>" + "["+g+"]"+"' value='"+value[g].source.name+"'/>"+
                                                        "<span id='getSectionid' class='section_" + g + "'>" +
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('author');?>" + "[" + g + "]" + "' value='" + value_widget_add[g].author + "'/>" +
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('urlToImage');?>" + "[" + g + "]" + "' value='" + value_widget_add[g].urlToImage + "'/>" +
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('publishedAt');?>" + "[" + g + "]" + "' value='" + value_widget_add[g].publishedAt + "'/>" +
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('content_title');?>" + "[" + g + "]" + "' value='" + value_widget_add[g].title + "'/>" +
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('content_description');?>" + "[" + g + "]" + "' value='" + value_widget_add[g].description + "'/>" +
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('url');?>" + "[" + g + "]" + "' value='" + value_widget_add[g].url + "'/>" +
                                                        "</span>"
                                                    );

                                                }
                                            }
                                        }
                                    });
                                });
//                            $(".showFromDatabase").html(" ");
                            });
                        }
                    });
                    $(document).on('widget-updated', function (event, widget) {
                        $("#<?php echo $this->get_field_id('showCheckedSite');?>").html("Widget save successfully").fadeOut(5000);
                        $(".category_block").css('display', 'block');
                        $(".message_category").css('display', 'none');
//                        alert("Widget has been saved successfully")
                    });
                    $(document).ready(function () {
                        $("#<?php echo $this->get_field_id('news_category_id')?>").on('change', function () {
                                news_category_value = this.value;
                                $("#<?php echo $this->get_field_id('news_category_id')?> option:selected").attr("selected", "selected");
                                var api_link = "https://newsapi.org/v2/everything?q=" + news_category_value + "&apiKey=7002deb905924b91b4841b5f4fc8e8ae";
                                $.getJSON(api_link, function (data) {
                                    var value = data.articles;
                                    console.log(value);
                                    var result = [];
                                    var temp_arr = [];
                                    for (var j = 0; j < data.articles.length; j++) {
                                        result.push(data.articles[j].source.name);
                                    }
    //                                console.log(result);
                                    for (var q = 0; q < result.length; q++) {
                                        if ($.inArray(result[q], temp_arr) == -1) {
                                            temp_arr.push(result[q]);
                                            if (news_category_value !== '') {
                                                $("#<?php echo $this->get_field_id('showSource');?>").css('display', 'block');
                                                $("#<?php echo $this->get_field_id('showSource');?>").append(
                                                    "<div class='sources_class'><input type='checkbox' class='input_source_class' value='" + result[q] + "'/>" + result[q] + "</div>"
                                                );
                                            } else {
                                                $("#<?php echo $this->get_field_id('showSource');?>").css('display', 'none');
                                            }
                                        }
                                    }
                                    $("input[type=checkbox]").change(function () {
                                        if ($(this).is(":checked")) {
                                            var checked_value = $(this).val();

                                            for (var i = 0; i < value.length; i++) {
                                                if (checked_value == value[i].source.name) {
                                                    if (value[i].author == null) {
                                                        value[i].author = "No author found";
                                                    }
                                                    if (value[i].urlToImage == null || value[i].urlToImage == "content image") {
                                                    }
                                                    $("#<?php echo $this->get_field_id('showFromDatabase');?>").append(
                                                        "<span id='getSectionid' class='section_" + i + "'>" +
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('author');?>" + "[" + i + "]" + "' value='" + value[i].author + "'/>" +
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('urlToImage');?>" + "[" + i + "]" + "' value='" + value[i].urlToImage + "'/>" +
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('publishedAt');?>" + "[" + i + "]" + "' value='" + value[i].publishedAt + "'/>" +
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('content_title');?>" + "[" + i + "]" + "' value='" + value[i].title + "'/>" +
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('content_description');?>" + "[" + i + "]" + "' value='" + value[i].description + "'/>" +
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('url');?>" + "[" + i + "]" + "' value='" + value[i].url + "'/>" +
                                                        "</span>"
                                                    );
                                                    $("#<?php echo $this->get_field_id('showCheckedSiteSave');?>").append(
                                                        "<input type='hidden' name='<?php echo $this->get_field_name('checkedItem')?>"+"["+i+"]"+"' value='"+value[i].source.name+"'/>"
                                                    );
                                                }
                                            }
                                        }
                                    });
                                });
                            $("#<?php echo $this->get_field_id('showSource');?>").html(" ");
                        });
                    });
                })(jQuery);
            </script>
        </div>
        <?php
    }
}
function WorldNewsFunction(){
    register_widget('WorldNews');
}
add_action('widgets_init','WorldNewsFunction');