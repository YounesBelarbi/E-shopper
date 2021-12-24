$(document).ready(function () {

    // Product detail
    $('.product-links-wap a').click(function () {
        var this_src = $(this).children('img').attr('src');
        $('#product-detail').attr('src', this_src);
        return false;
    });
    $('#btn-reduce').click(function () {
        var val = $("#var-value").html();
        val = (val == '1') ? val : val - 1;
        $("#var-value").html(val);
        $("#product-quantity").val(val);
        return false;
    });
    $('#btn-add').click(function () {
        var val = $("#var-value").html();
        val++;
        $("#var-value").html(val);
        $("#order_item_form_quantity").val(val);
        return false;
    });
    $('.btn-option').click(function () {
        var this_val = $(this).html();
        $("#product-option").val(this_val);
        $(".btn-option").removeClass('btn-secondary');
        $(".btn-option").addClass('btn-success');
        $(this).removeClass('btn-success');
        $(this).addClass('btn-secondary');
        return false;
    });
    // End product detail

});
