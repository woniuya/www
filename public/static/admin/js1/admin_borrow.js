$(function(){
    $(".block-bordered").hide();
    $("input:radio[name='v_status']").change(function(){
        if($("input:radio[name='v_status']:checked").val()==1){
            $(".block-bordered").show();
        }else{
            $(".block-bordered").hide();
        }
    })

    $("#stock_subaccount_id_v").change(function(){

        var risk = $(this).val();
        $.post('/admin.php/stock/borrow/risk.html', {'id':risk}, function(data){
            setStock(data);
        })
    })
    $("#stock_subaccount_id_r").change(function(){

        var risk = $(this).val();
        $.post('/admin.php/stock/borrow/risk.html', {'id':risk}, function(data){
            setStock(data);
        })
    })
});

function setStock(data)
{
    var d = eval("("+data+")");
    $("input[name='prohibit_close'][value="+d.prohibit_close+"]").attr("checked",true);
    $("input[name='prohibit_open'][value="+d.prohibit_open+"]").attr('checked',true);
    $("input[name='prohibit_back'][value="+d.prohibit_back+"]").attr("checked",true);
    $("input[name='renewal'][value="+d.renewal+"]").attr('checked',true);
    $("input[name='autoclose'][value="+d.autoclose+"]").attr('checked',true);

    // $("#loss_warn").val(d.loss_warn);
    // $("#loss_close").val(d.loss_close);
    // $("#position").val(d.position);

    $("#commission_scale").val(d.commission_scale);
    $("#min_commission").val(d.min_commission);
    $("#rate_scale").val(d.rate_scale);
    $("#profit_share_scale").val(d.profit_share_scale);

}