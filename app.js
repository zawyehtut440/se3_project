function loadURL(url) {
    fetch(url, {
        method: 'POST',
    })
    .then(function (response) {
        return response.text();
    })
    .then(function (data) {
        let div = document.getElementById('main');
        div.innerHTML = data;
    })
}

function loadSubURL(url) {
    fetch(url, {
        method: 'POST',
    })
    .then(function (response) {
        return response.text();
    })
    .then(function (data) {
        let div = document.getElementById('subMain');
        div.innerHTML = data;
    })
}

/**
 * user function
*/

// fetch form data from login or register
function fetchFormData(action) {
    let formID = action + 'Form';
    let form = document.getElementById(formID);
    let mydat = new FormData(form);
    // Get userID from cookies
    let userID = Cookies.get('userID');
    if (userID) {
        mydat.append('userID', userID); // Append userID to FormData
    }
    let url = './user/userController.php?act=' + action;
    fetch(url, {
        method: 'POST',
        body: mydat,
    })
    .then(function (response) {
        return response.json();
    })
    .then(function (data) {
        if (action === 'login') {
            // get user's id and role
            let userID = data["userID"];
            let userRole = data["role"];
            // set user's id and role cookies
            Cookies.set('userID', userID);
            Cookies.set('userRole', userRole);
            // load page base on userRole
            let role = Cookies.get('userRole');
            let id = Cookies.get('userID');
            loadURL(getRedirectPage(role));
        } else if (action === 'register') {
            // do something ..........
            loadURL('login.html');
        }
    });
}

function getRedirectPage(role) {
    if (role === '客戶') {
        return 'customer/customer.html';
    } else if (role === '商家') {
        return 'supplier/supplierView.html';
    } else if (role === '物流') {
        return 'logistics/logistics.html';
    } else {
        return 'error.html';
    }
}

function logout() {
    Cookies.remove('userID');
    Cookies.remove('userRole');
    // relaod page
    window.location.reload();
}

function register() {
    // if have already login
    let userID = Cookies.get('userID');
    if (userID) {
        // clear subMain
        document.getElementById('subMain').innerHTML = '';
    }
    // loadURL register.html
    loadURL('register.html');
}

/**
 * customer function
 */

function loadProduct() {
    let url = './customer/customerController.php?act=loadProduct';
    fetch(url, {
        method: 'POST',
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        let div = document.getElementById('subMain');
        let tbHead = ['商品ID', '商家ID', '商品名稱', '商品介紹', '商品價格', '數量'];
        let result = showAllProduct(tbHead, data);
        div.innerHTML = result;
    });
}

function showAllProduct(tbHead, data) {
    let result = '<table border=1>';
    // cope with table header first
    result += '<tr>';
    for (let thead of tbHead) {
        result += '<th>' + thead + '</th>'
    }
    result += '<th>-</th>';
    result += '</tr>';
    // then cope with table body
    for (let r of data) {
        result += '<tr>';
        for (let key in r) {
            result += "<td>" + r[key] + "</td>";
        }
        let productID = r['productID'];
        // quantity
        result += `<td><input id="myNum${productID}" type="number" name="quantity${productID}" value="1"`+'></td>';
        // add cart
        result += "<td><button onclick='addCart(" + productID + ")'>加入購物車</button></td>";
        result += "</tr>";
    }
    result += '</table>';
    return result;
}

function addCart(productID) {
    let inputElement = document.getElementById('myNum'+productID);
    let quantity = inputElement.value;
    let url = './customer/customerController.php?act=addCart&productID='+ productID +'&quantity=' + quantity;
    let mydat = new FormData();
    mydat.append('customerID', Cookies.get('userID'))
    fetch(url, {
        method: 'POST',
        body: mydat,
    })
    .then(function(response) {
        return response.text();
    });
}

function loadCart() {
    // load customer shoppig cart base on customer's userID
    let url = './customer/customerController.php?act=loadCart'
    let mydat = new FormData();
    mydat.append('customerID', Cookies.get('userID'));
    fetch(url, {
        method: 'POST',
        body: mydat,
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        let div = document.getElementById('subMain');
        let tbHead = ['商品ID', '商品名稱', '商品價格', '購買數量', '總價'];
        let result = showCartTable(tbHead, data);
        div.innerHTML = result;
    });
}

function showCartTable(tbHead, data) {
    let result = '<table border=1>';
    // cope with table header first
    result += '<tr>';
    for (let thead of tbHead) {
        result += '<th>' + thead + '</th>'
    }
    result += '<th>-</th>';
    result += '</tr>';
    // then cope with table body
    for (let r of data) {
        result += '<tr>';
        for (let key in r) {
            if (key !== 'cartID') {
                result += "<td>" + r[key] + "</td>";
            }
        }
        // total price
        let totalPrice = Number(r['price']) * Number(r['quantity']);
        result += '<td>' + totalPrice + '</td>'
        let cartID = r['cartID'];
        result += "<td><button onclick='delCartProduct(" + cartID + ")'>刪</button></td>";
        result += "</tr>"
    }
    result += '</table>';
    return result;
}

function checkout() {
    let url = './customer/customerController.php?act=checkout';
    let mydat = new FormData();
    mydat.append('customerID', Cookies.get('userID'));
    fetch(url, {
        method: 'POST',
        body: mydat,
    })
    .then(function(response) {
        return response.text();
    })
    .then(function(data) {
        console.log('echo value from the server' + data);
        let div = document.getElementById('subMain');
        div.innerHTML = '已完成結帳, <button onclick="viewOrderStatus()">查看訂單狀態</button>';
    });
}

function viewOrderStatus() {
    let url = './customer/customerController.php?act=viewOrderStatus';
    let mydat = new FormData();
    mydat.append('customerID', Cookies.get('userID'));
    fetch(url, {
        method: 'POST',
        body: mydat,
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        let div = document.getElementById('subMain');
        let tbHead = ['訂單編號', '商家ID', '商家名稱', '定單狀態'];
        let result = showOrderTable(tbHead, data);
        div.innerHTML = result;
    });
}

function showOrderTable(tbHead, data) {
    let orderStatuses = ['未處理', '處理中', '寄送中', '已寄送', '已送達']
    let result = '<table border=1>';
    // cope with table header first
    result += '<tr>';
    for (let thead of tbHead) {
        result += '<th>' + thead + '</th>'
    }
    result += '<th>-</th>';
    result += '</tr>';
    // then cope with table body
    for (let r of data) {
        result += '<tr>';
        for (let key in r) {
            if (key === 'orderStatus') {
                result += '<td>' + orderStatuses[r[key]] + '</td>'
            } else if (key !== 'rating') {
                result += "<td>" + r[key] + "</td>";
            }
        }
        let orderID = r['orderID'];
        result += '<td><button onclick="viewOrderDetail(' + orderID + ')">查看訂單詳情</button></td>';
        result += "</tr>"
    }
    result += '</table>';
    return result;
}

function viewOrderDetail(orderID) {
    let url = './customer/customerController.php?act=viewOrderDetail&orderID=' + orderID;
    fetch(url, {
        method: 'GET',
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        // showing the specific orderID orderItems
        let div = document.getElementById('subMain');
        let tbHead = ['訂單編號', '商品ID', '商品名稱', '購買數量', '價格', '商品總價'];
        let result = showOrderDetailTable(tbHead, data);
        div.innerHTML = result;
    });
}

function showOrderDetailTable(tbHead, data) {
    let allProductsPrice = 0;
    let result = '<table border=1>';
    // cope with table header first
    result += '<tr>';
    for (let thead of tbHead) {
        result += '<th>' + thead + '</th>'
    }
    result += '</tr>';
    // then cope with table body
    for (let r of data) {
        result += '<tr>';
        for (let key in r) {
            result += "<td>" + r[key] + "</td>";
        }
        let totalPrice = Number(r['price']) * Number(r['quantity']);
        allProductsPrice += totalPrice;
        result += '<td>' + totalPrice + '</td>'
        result += "</tr>";
    }
    result += '</table>';
    result += '<br>總金額: ' + allProductsPrice + '元';
    result += '<button onclick="viewOrderStatus()">查看其他訂單狀態</button>';
    return result;
}

function ratingDeliveredOrder() {
    // load order which is delivered
    let url = './customer/customerController.php?act=viewOrderStatus';
    let mydat = new FormData();
    mydat.append('customerID', Cookies.get('userID'));
    fetch(url, {
        method: 'POST',
        body: mydat,
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        // showing order that is delivered
        let div = document.getElementById('subMain');
        let tbHead = ['訂單編號', '商家ID', '商家名稱', '定單狀態', '給評價'];
        let result = showOrderDeliveredTable(tbHead, data);
        div.innerHTML = result;
    });
}

function optionSelect(orderID) {
    let result = `<select id="${orderID}rating" name="${orderID}rating">`;
    result += '<option selectd value="1">1</option>'
    result += '<option value="2">2</option>'
    result += '<option value="3">3</option>'
    result += '<option value="4">4</option>'
    result += '<option value="5">5</option>'
    result += '</select>';
    return result;
}

function showOrderDeliveredTable(tbHead, data) {
    let orderStatuses = ['未處理', '處理中', '寄送中', '已寄送', '已送達']
    let result = '<table border=1>';
    // cope with table header first
    result += '<tr>';
    for (let thead of tbHead) {
        result += '<th>' + thead + '</th>'
    }
    result += '<th>-</th>';
    result += '</tr>';
    // then cope with table body
    for (let r of data) {
        if (r['orderStatus'] === 4 && r['rating'] === 0) {
            result += '<tr>';
            let orderID = r['orderID'];
            // showing out
            for (let key in r) {
                if (key === 'orderStatus') {
                    result += '<td>' + orderStatuses[r[key]] + '</td>'
                } else if (key === 'rating') {
                    result += '<td>' + optionSelect(orderID)+ '</td>';
                } else {
                    result += "<td>" + r[key] + "</td>";
                }
            }
            result += '<td><button onclick="rating(' + orderID + ')"> 評價</button></td>';
            result += "</tr>"
        }
    }
    result += '</table>';
    return result;
}

function rating(orderID) {
    let ratingValue = document.getElementById(`${orderID}rating`).value;
    let url = './customer/customerController.php?act=rating&orderID=' + orderID + '&ratingValue=' + ratingValue;
    fetch(url, {
        method: 'GET',
    })
    .then(function(response) {
        // load ratingDeliveredOrder
        ratingDeliveredOrder();
    });
}

function delCartProduct(cartID) {
    let url = './customer/customerController.php?act=delCartProduct&cartID=' + cartID;
    fetch(url, {
        method: 'GET',
    })
    .then(function(response) {
        loadCart();
    });
}

/**
 * supplier function
 */

function loadList() {
    let userID = Cookies.get('userID');
    let url = "./supplier/supplierControl.php?act=listItem&id=" + userID;
    fetch(url, {
        method: 'GET',
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        let div = document.getElementById('subMain');
        let result = "<table border=1>";
        result += '<tr><th>id</th><th>商品名稱</th><th>價格</th><th>敘述</th>';
        result += '<th>-</th><th>-</th></tr>'
        for (let r of data) {
            result += "<tr>";

            for (let key in r) {
                result += "<td>"+r[key]+"</td>";
            }
            result += "<td><button onclick='loadEditForm(" + r['productID'] + ")'>修改</button></td>";
            result += "<td><button onclick='delItem(" + r['productID'] + ")'>刪</button></td>";

            result += "</tr>"
        }
        result += "</table>"
        div.innerHTML = result;
    })
}

function loadEditForm(id) {
    let url = './supplier/supplierControl.php?act=getItemInfo&id=' + id;
    fetch(url, {
        method: 'GET',
    })
    .then(function (response) {
        return response.json();
    })
    .then(function(data) {
        let form = loadUpdateFormUI(data)
        let div = document.getElementById('subMain');
        div.innerHTML = form;
    })
}

function loadUpdateFormUI(data) {
    let result = '<form id="myForm" name="myForm" method="post">';
    result += '商品名稱: <input name="name" type="text" value="' + data['name'] + '" ><br>';
    result += '商品價格: <input name="price" type="number" value="' + data['price'] + '" ><br>';
    result += '商品資訊: <input name="description" type="text" value="' + data['description'] + '" ><br>';
    result += '<input type="button" onclick="updateItem('+ data['productID'] + ')" value="修改">';
    result += '</form>';
    return result;
}

function addItem() {
    let userID = Cookies.get('userID');
    let form = document.getElementById('myForm');
    let mydat = new FormData(form);
    mydat.append('userID', userID);

    let url = './supplier/supplierControl.php?act=addItem';
    fetch(url, {
        method: 'POST',
        body: mydat,
    })
    .then(function(response) {
        return response.text();
    })
    .then(function(data) {
        loadList();
    })
}

function updateItem(id) {
    let form = document.getElementById('myForm');
    let mydat = new FormData(form);
    let url = './supplier/supplierControl.php?act=updateItem&id=' + id;
    fetch(url, {
        method: 'POST',
        body: mydat,
    })
    .then(function(response) {
        return response.text();
    })
    .then(function(data) {
        loadList();
    })
}

function delItem(id) {
    let url = './supplier/supplierControl.php?act=delItem&id=' + id;
    fetch(url, {
        method: 'GET',
    })
    .then(function(response) {
        loadList();
    })
}

function loadOrders() {
    let id = Cookies.get('userID');
    let url = "./supplier/supplierControl.php?act=orders&id=" + id;
    fetch(url, {
        method: 'GET',
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        let div = document.getElementById('subMain');
        let result = "<table border=1>";
        result += '<tr><th>訂單序號</th><th>客戶名稱</th><th>訂單狀態</th>';
        result += '<th>詳情</th><th>處理/寄送</th></tr>'
        for (let r of data) {
            result += "<tr>";

            let keys = Object.keys(r);
            for (let i = 0; i < keys.length; i++) {
                let key = keys[i];
                if (key === 'orderStatus') {
                    continue;
                }
                result += "<td>" + r[key] + "</td>";
            }
            result += "<td><button onclick='loadOrderItems(" + r['orderID'] + ")'>詳情</button></td>";
            if (r['orderStatus'] === 0) {
                result += "<td><button onclick='changeOrderStatus(" + r['orderID'] + "," + r['orderStatus'] + ")'>處理訂單</button></td>";
            } else if (r['orderStatus'] === 1) {
                result += "<td><button onclick='changeOrderStatus(" + r['orderID'] + "," + r['orderStatus'] + ")'>寄送訂單</button></td>";
            } 
            result += "</tr>"
        }
        result += "</table>"
        div.innerHTML = result;
    })
}

function loadOrderItems(id) {
    let url = './supplier/supplierControl.php?act=getOrderItemsInfo&id=' + id;
    fetch(url, {
        method: 'GET',
    })
    .then(function (response) {
        return response.json();
    })
    .then(function(data) {
        let form = loadOrderItemsUI(data)
        let div = document.getElementById('subMain');
        div.innerHTML = form;
    })
}

function loadOrderItemsUI(data) {
    let result = '<table border=1>';
    result += '<tr><th>訂單商品序號</th><th>客戶名稱</th><th>商品名稱</th><th>所需數量</th></tr>';
    
    for (let r of data) {
        result += "<tr>";
        result += "<td>"+r['orderItemID']+"</td>";
        result += "<td>"+r['userName']+"</td>";
        result += "<td>"+r['name']+"</td>";
        result += "<td>"+r['quantity']+"</td>";
        result += "</tr>";
    }
    result += "</table>";
    return result;
};

function changeOrderStatus(id, status) {
    let url = './supplier/supplierControl.php?act=changeOrderStatus&id=' + id + '&status=' + status;
    fetch(url, {
        method: 'GET',
    })
    .then(function(response) {
        loadOrders();
    })
}