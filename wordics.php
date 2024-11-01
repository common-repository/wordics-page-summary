<?php
/*
Plugin Name: Wordics: Page Summary
Plugin URI: http://wordics.lemmatica.com/
Description: Provides a nice summary of each page in the form of a colorful word cloud where important words stand out. Also, it enables a small HTML popup to appear that summarizes every linked page from your posts as the user hovers over the link with the mouse.
Author: Blue Edge Bulgaria
Version: 1
Author URI: http://www.blue-edge.bg/
*/

/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/


//error_reporting(E_ALL);

add_action('init', array('Wordics', 'myWordics_init'));
register_activation_hook( __FILE__, array('Wordics', 'activate'));
register_deactivation_hook( __FILE__, array('Wordics', 'deactivate'));

function print_colorSchemes($num,$arr){
    if ($num == '-2') { echo 'colorScheme: { "bg": "'.$arr['custom_bg'] .'",
                                                "colors" : ["'.$arr['custom_c1'].'", 
                                                            "'.$arr['custom_c2'].'", 
                                                            "'.$arr['custom_c3'].'", 
                                                            "'.$arr['custom_c4'].'"] },';}
    elseif ($num != '-1') { echo 'colorScheme:' . $num .','; }
  }
class Wordics {

  function widget_myWordics($args)  {
    extract($args);      
    $options = get_option('Wordics');
    if (empty($options['tag_links']) ) { $options['tag_links'] = 'false'; }
    if (empty($options['topN']) ) { $options['topN'] = '25'; }
    ?>

    
    <?php
    echo $before_widget;
    echo $before_title;?><a href='http://wordics.lemmatica.com'>Wordics</a><?php echo $after_title;
    
    ?>  
    <script type="text/javascript" src="http://wordics.lemmatica.com/static/wordics.js"> </script>
    <script type="text/javascript">
      wordics({ topN: <?php echo $options['topN']; ?> , 
                <?php print_colorSchemes($options['colorScheme'],$options['customColors']); ?>
                tag_links: <?php echo $options['tag_links']; ?> ,
                wordpress:true
                });  
    </script>
<?php
   if (!empty($options['word_cloud'])) { ?>
   
   <div id="wordics_container" style="
    width:  <?php echo empty($options['width'])?'100%':$options['width']; ?> ; 
    height: <?php echo empty($options['height'])?'200px':$options['height']; ?> ;
    border: 1px solid black;">
        <a href="http://wordics.lemmatica.com">Wordics</a> is loading.
          <img alt="waiting" src="http://wordics.lemmatica.com/static/ajax-loader.gif" />        
   </div>
    
    <?php }
    echo $after_widget;
    }
    
    
    
  function myWordics_control() {
    
    $data = get_option('Wordics');    
    if (isset($_POST['Wordics-Submit'])){
      $data['width'] = $_POST['width'];
      $data['height'] = $_POST['height'];
      $data['topN'] = $_POST['topN'];    
      $data['tag_links'] = $_POST['tag_links'];   
      $data['word_cloud'] = $_POST['word_cloud'];   
      $data['first_time'] = 'false';
      $data['colorScheme'] = $_POST['colorScheme'];
      $data['customColors'] = array('custom_bg' => $_POST['custom_bg'],
                                    'custom_c1' => $_POST['custom_c1'],
                                    'custom_c2' => $_POST['custom_c2'],
                                    'custom_c3' => $_POST['custom_c3'],
                                    'custom_c4' => $_POST['custom_c4']);
  
      update_option('Wordics', $data);
    }
    ?>
    <p>
      <input name="word_cloud" type="checkbox" value="true" <?php echo empty($data['word_cloud'])?'':'checked'; ?> /> <label for="word_cloud" ><b>Word Cloud</b></label>    
      <input style="margin-left:15px;" name="tag_links" type="checkbox" value="true" <?php echo empty($data['tag_links'])?'':'checked'; ?> /> <label for="tag_links"><b>Popup </b></label>    
    </p>
    <table style="width:100%;">
      <tr>
        <td style="width:200px"><label for="name"><b>Width:</b></label></td>
        <td><input name="width" type="text" value="<?php echo $data['width']; ?>" /></td>
      </tr> 
      <tr>
        <td><label for="height"><b>Height:</b></label></td>
        <td><input name="height" type="text" value="<?php echo $data['height']; ?>" /></td>
      </tr> 
      <tr>
        <td><label for="topN"><b>Max. number of words:</b></label></td>
        <td><input style="width:100%" name="topN" type="text" value="<?php echo $data['topN']; ?>" /></td>
      </tr> 
    </table>
    <p style="margin-bottom:5px;"><b>Color scheme:</b> <br />
    
    <table style="width:100%; margin-bottom:10px;">
      <tr>
        <td colspan=2>
          <input id="random_rb" name="colorScheme" value="-1" style="margin-left: 0px; top: 4px;" <?php echo ($data['colorScheme']=='-1')?'checked="checked" selected="selected"':'' ?> type="radio">      
          <label for="random_rb">Random</label>           
        </td>        
      </tr>
      <tr>
        <td><input value="0"  name="colorScheme" id="cs_0" style="float: left;" type="radio" <?php echo ($data['colorScheme']=='0')?'checked="checked" selected="selected"':'' ?> ><label for="cs_0" style="float: left;"><div style="border: 1px dotted black; background: white none repeat scroll 0% 0%; height: 16px; width: 14px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left;"><div style="border: 1px solid white; background: black none repeat scroll 0% 0%; margin-left: 1px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div></div></label></td>  
        <td><input value="1"  name="colorScheme" id="cs_1" style="float: left;" type="radio" <?php echo ($data['colorScheme']=='1')?'checked="checked" selected="selected"':'' ?> ><label for="cs_1" style="float: left;"><div style="border: 1px dotted black; background: black none repeat scroll 0% 0%; height: 16px; width: 14px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left;"><div style="border: 1px solid black; background: white none repeat scroll 0% 0%; margin-left: 1px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div></div></label></td>
      </tr>
      <tr>
        <td><input value="2"  name="colorScheme" id="cs_2" style="float: left;" type="radio" <?php echo ($data['colorScheme']=='2')?'checked="checked" selected="selected"':'' ?> ><label for="cs_2" style="float: left;"><div style="border: 1px dotted black; background: black none repeat scroll 0% 0%; height: 16px; width: 38px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left;"><div style="border: 1px solid black; background: red none repeat scroll 0% 0%; margin-left: 1px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: blue none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: green none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div></div></label></td>  
        <td><input value="3"  name="colorScheme" id="cs_3" style="float: left;" type="radio" <?php echo ($data['colorScheme']=='3')?'checked="checked" selected="selected"':'' ?> ><label for="cs_3" style="float: left;"><div style="border: 1px dotted black; background: white none repeat scroll 0% 0%; height: 16px; width: 50px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left;"><div style="border: 1px solid white; background: rgb(0, 0, 0) none repeat scroll 0% 0%; margin-left: 1px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid white; background: rgb(51, 51, 51) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid white; background: rgb(102, 102, 102) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid white; background: rgb(136, 136, 136) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div></div></label></td>
      </tr>
      <tr>
        <td><input value="4"  name="colorScheme" id="cs_4" style="float: left;" type="radio" <?php echo ($data['colorScheme']=='4')?'checked="checked" selected="selected"':'' ?> ><label for="cs_4" style="float: left;"><div style="border: 1px dotted black; background: black none repeat scroll 0% 0%; height: 16px; width: 62px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left;"><div style="border: 1px solid black; background: rgb(141, 195, 242) none repeat scroll 0% 0%; margin-left: 1px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(203, 228, 248) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(242, 242, 242) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(140, 191, 31) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(122, 166, 27) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div></div></label></td>  
        <td><input value="5"  name="colorScheme" id="cs_5" style="float: left;" type="radio" <?php echo ($data['colorScheme']=='5')?'checked="checked" selected="selected"':'' ?> ><label for="cs_5" style="float: left;"><div style="border: 1px dotted black; background: black none repeat scroll 0% 0%; height: 16px; width: 62px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left;"><div style="border: 1px solid black; background: rgb(190, 124, 0) none repeat scroll 0% 0%; margin-left: 1px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(190, 60, 0) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(190, 187, 0) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(255, 179, 36) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(255, 179, 36) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div></div></label></td>
      </tr>
      <tr>
        <td><input value="6"  name="colorScheme" id="cs_6" style="float: left;" type="radio" <?php echo ($data['colorScheme']=='6')?'checked="checked" selected="selected"':'' ?> ><label for="cs_6" style="float: left;"><div style="border: 1px dotted black; background: black none repeat scroll 0% 0%; height: 16px; width: 62px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left;"><div style="border: 1px solid black; background: rgb(85, 95, 12) none repeat scroll 0% 0%; margin-left: 1px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(95, 77, 12) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(57, 95, 12) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(166, 185, 23) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(53, 53, 53) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div></div></label></td>  
        <td><input value="7"  name="colorScheme" id="cs_7" style="float: left;" type="radio" <?php echo ($data['colorScheme']=='7')?'checked="checked" selected="selected"':'' ?> ><label for="cs_7" style="float: left;"><div style="border: 1px dotted black; background: black none repeat scroll 0% 0%; height: 16px; width: 62px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left;"><div style="border: 1px solid black; background: rgb(255, 161, 0) none repeat scroll 0% 0%; margin-left: 1px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(255, 75, 0) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(255, 245, 0) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(255, 198, 101) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div><div style="border: 1px solid black; background: rgb(127, 127, 127) none repeat scroll 0% 0%; margin-left: 0px; height: 10px; width: 10px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; float: left; position: relative; top: 2px; font-size: 0px;"></div></div></label></td>
      </tr>      
    </table>

      <input id="custom_rb" name="colorScheme" value="-2" type="radio" <?php echo ($data['colorScheme']=='-2')?'checked="checked" selected="selected"':'' ?>><label for="custom_rb">Custom: </label>
      <div style="float: left;">
        Backgr: <input id="custom_bg" name="custom_bg" value="<?php echo $data['customColors']['custom_bg'] ?>" type="text">
        Color1: <input id="custom_c1" name="custom_c1" value="<?php echo $data['customColors']['custom_c1'] ?>" type="text">
        Color2: <input id="custom_c2" name="custom_c2" value="<?php echo $data['customColors']['custom_c2'] ?>" type="text">
        Color3: <input id="custom_c3" name="custom_c3" value="<?php echo $data['customColors']['custom_c3'] ?>" type="text">
        Color4: <input id="custom_c4" name="custom_c4" value="<?php echo $data['customColors']['custom_c4'] ?>" type="text">
      </div>                   
    </p>
            
    <input type="hidden" id="Wordics-Submit" name="Wordics-Submit" value="1" />
    
    <?php   
  }
  function myWordics_init()
    {
    register_sidebar_widget('Wordics', array('Wordics','widget_myWordics'));
    register_widget_control('Wordics', array('Wordics', 'myWordics_control'));
    }
    
  function activate(){
    $defaults = array( 'width' => '99%' ,
                   'height' => '200px', 
                   'topN' => '25', 
                   'tag_links' => 'false', 
                   'word_cloud' => 'true',
                   'colorScheme' => '-1',
                   'customColors' => array ('custom_bg' => 'black',
                                            'custom_c1' => 'red',
                                            'custom_c2' => 'yellow',
                                            'custom_c3' => 'blue',
                                            'custom_c4' => 'white'));
    
    if ( ! get_option('Wordics')){ add_option('Wordics' , $defaults); } 
    else { update_option('Wordics' , $defaults); }
  }
  
  function deactivate(){ delete_option('Wordics'); }
}

?>