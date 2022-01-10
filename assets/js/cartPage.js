const axios = require('axios').default;

const app = {
    init: function () {
        $(".cart-btn-reduce").on("click", app.handleChangeQuantity);
        $(".cart-btn-add").on("click", app.handleChangeQuantity);
    },
    handleChangeQuantity: function (e) {
        e.preventDefault();

        let elementClicked = $(e.target);
        let quantityElementsGroup = elementClicked.closest(".input-group");
        let orderItemId = quantityElementsGroup.data("id");
        let inputElement = quantityElementsGroup.find(".cart-product-quantity");
        let productQuantity = inputElement.val();

        if (elementClicked.hasClass("minus")) {
            productQuantity = (productQuantity == '1') ? productQuantity : productQuantity - 1;
            inputElement.val(productQuantity);
            app.requestToChangeProductQuantity(orderItemId, productQuantity);
        } else if (elementClicked.hasClass("plus")) {
            productQuantity++;
            inputElement.val(productQuantity);
            app.requestToChangeProductQuantity(orderItemId, productQuantity);
        }
    },
    requestToChangeProductQuantity: function (orderItemId, productQuantity) {
        axios.post('/cart/product/quantity', {
            orderItemId: orderItemId,
            productQuantity: productQuantity,
        })
            .then(function (response) {
                console.log(response.data.orderItemTotal);
                $(".item-" + orderItemId).html(response.data.orderItemTotal.toFixed(2) + " €");
                $(".order-total").html(response.data.orderTotal.toFixed(2) + " €");
            })
            .catch(function (error) {
                console.log(error);
            });
    }
};
$(app.init);
