<?php
function page_index($adm)
{
    global $ML_TAG_TYPE , $ML_COLOR , $ML_COLOR_RGB , $ML_CATELOG;
?>
<form action="?api=batch_audit" method="post">
<table class="adminlist" width="100%">
    <tr>
        <th><input type="submit" value="保存并跳到下一页"/></th>
    </tr>
    <tr>
        <td>

<?php
    foreach ($adm['goods'] as $key => $value) {
?>

<div style="float:left;width:230px">
<table class="adminlist" width="150px">
    <tr>
        <td>
            <img src="<?php echo ml_tool_picid::pid2url($value['pic_id']); ?>"/>
            <input type="hidden" name="rid[]" value="<?php echo $value['rid']; ?>"/>
        </td>
    </tr>
    <tr>
        <td><?php echo Tool_string::un_html($value['gd_title']); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><font color="#ff0000"><?php echo $value['gd_price']; ?></font></b></td>
    </tr>
    <tr>
        <td><input type="text" name="tag[<?php echo $value['rid']; ?>]" value="<?php echo Tool_string::un_html($value['gd_tag']); ?>"/></td>
    </tr>
    <tr>
        <td><?php echo $value['gd_price']; ?></td>
    </tr>
    <tr>
        <td>
            <input type="hidden" name="ctg[<?php echo $value['rid'] ?>]" value="<?php echo $value['gd_catelog']; ?>"/>
            <?php foreach ($ML_CATELOG as $ctg_id => $ctg_name) {?>
                <div value="<?php echo $ctg_id; ?>" class="cls_ctg" style="float:left;border: <?php if($ctg_id == $value['gd_catelog']){ ?>2px solid #ff0000<?php } ?>"><a href="javascript:;"/><?php echo $ctg_name; ?></a> </div>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td>
            <input type="hidden" name="color[<?php echo $value['rid'] ?>]" value="<?php echo $value['color']; ?>"/>
            <?php foreach ($ML_COLOR_RGB as $color_id => $rgb) {?>
                <div value="<?php echo $color_id; ?>" class="cls_color" style="float:left;background: <?php echo $rgb; ?>;height:20px;border: <?php if($color_id == $value['color']){ ?>2px solid #ff0000<?php } ?>"><a href="javascript:;"/>&nbsp;&nbsp;&nbsp;&nbsp;</a></div>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td>
            <input type="hidden" name="pt[<?php echo $value['rid'] ?>]" value="0"/>
            <?php for($i=0;$i<5;$i++){
                    $rgb = '#ff'.dechex((5-$i)*50).dechex((5-$i)*50).'';
                ?>
                <div value="<?php echo $i; ?>" class="cls_pt" style="float:left;background: <?php echo $rgb; ?>;height:20px;border: <?php if($i == 0){ ?>2px solid #ff0000<?php } ?>"><a href="javascript:;"/>&nbsp;&nbsp;<?php echo $i; ?>&nbsp;&nbsp;</a></div>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td>
            <input type="hidden" name="sex[<?php echo $value['rid'] ?>]" value="<?php echo $value['sex']; ?>"/>
            <div value="0" class="cls_sex" style="text-align: center; background-color:gray;float:left;width:50px;border: <?php if(0 == $value['sex']){ ?>2px solid #ff0000<?php } ?>"><a href="javascript:;"/>全部</a></div>
            <div value="1" class="cls_sex" style="text-align: center;background-color:green;float:left;width:50px;border: <?php if(1 == $value['sex']){ ?>2px solid #ff0000<?php } ?>"><a href="javascript:;"/>男</a></div>
            <div value="2" class="cls_sex" style="text-align: center;background-color:pink;float:left;width:50px;border: <?php if(2 == $value['sex']){ ?>2px solid #ff0000<?php } ?>"><a href="javascript:;"/>女</a></div>
        </td>
    </tr>
</table>
</div>
<?php
    }
?>
        </td>
    </tr>
    <tr>
        <th><input type="submit" value="保存并跳到下一页"/></th>
    </tr>
</table>

</form>
<script type="text/javascript">
{
    $('div.cls_ctg').click(function(){
        $(this).parent().children('div').each(function(){
            $(this).css('border','');
        })
        $(this).css('border' , '2px solid #ff0000');
        $(this).parent().children('input:first').val($(this).attr('value'));
    });

    $('div.cls_color').click(function(){
        $(this).parent().children('div').each(function(){
            $(this).css('border','');
        })
        $(this).css('border' , '2px solid #ff0000');
        $(this).parent().children('input:first').val($(this).attr('value'));
    });

    $('div.cls_sex').click(function(){
        $(this).parent().children('div').each(function(){
            $(this).css('border','');
        })
        $(this).css('border' , '2px solid #ff0000');
        $(this).parent().children('input:first').val($(this).attr('value'));
    });

    $('div.cls_pt').click(function(){
        $(this).parent().children('div').each(function(){
            $(this).css('border','');
        })
        $(this).css('border' , '2px solid #ff0000');
        $(this).parent().children('input:first').val($(this).attr('value'));
    });
}
</script>

<?php
}
?>
