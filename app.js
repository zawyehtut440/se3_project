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
        return 'supplier/supplier.html';
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

// loadEditForm
function loadEditForm(id) {
    let url = './supplier/supplierController.php?act=getItemInfo&id=' + id;
    fetch(url, {
        method: 'GET',
    })
    .then(function (response) {
        return response.json();
    })
    .then(function (data) {
        let form = loadUpdateFormUI(data)
        let div = document.getElementById('subMain');
        div.innerHTML = form;
    });
}

function loadUpdateFormUI(data) {
    console.log(data);
    let result = '<form id="myForm" name="myForm" method="post">';
    result += '商品名稱: <input name="productName" type="text" value="' + data['name'] + '" ><br>';
    result += '商品介紹: <input name="productDescription" type="text" value="' + data['description'] + '" ><br>';
    result += '商品價格: <input name="productPrice" type="number" value="' + data['price'] + '" ><br>';
    result += '<input type="button" onclick="updateItem(' + data['productID'] + ')" value="修改">';
    result += '</form>';
    return result;
}

function updateItem(id) {
    let form = document.getElementById('myForm');
    let mydat = new FormData(form);
    let url = './supplier/supplierController.php?act=updateItem&id=' + id;
    fetch(url, {
        method: 'POST',
        body: mydat,
    })
    .then(function (response) {
        supplierLoadProductList();
    });
}

// delItem
function delItem(id) {
    let url = './supplier/supplierController.php?act=delItem&id=' + id;
    fetch(url, {
        method: 'GET',
    })
    .then(function(response) {
        supplierLoadProductList();
    });
}

function supplierLoadProductList() {
    let url = './supplier/supplierController.php?act=listItem';
    let mydat = new FormData();
    mydat.append('supplierID', Cookies.get('userID'));
    fetch(url, {
        method: 'POST',
        body: mydat,
    })
    .then(function (response) {
        return response.json();
    })
    .then(function (data) {
        // print out the list of product that supplier had added
        let div = document.getElementById('subMain');
        let tbHead = ['商品ID', '商家ID', '商品名稱', '商品介紹', '商品價格'];
        let result = showProductListTable(tbHead, data);
        div.innerHTML = result;
    });
}

// show table on page
function showProductListTable(tbHead, data) {
    let result = '<table border=1>';
    // cope with table header first
    result += '<tr>';
    for (let thead of tbHead) {
        result += '<th>' + thead + '</th>'
    }
    result += '<th>-</th><th>-</th>';
    result += '</tr>';
    // then cope with table body
    for (let r of data) {
        result += '<tr>';
        for (let key in r) {
            result += "<td>" + r[key] + "</td>";
        }
        let id = r['productID'];
        result += "<td><button onclick='loadEditForm(" + id + ")'>修改</button></td>";
        result += "<td><button onclick='delItem(" + id + ")'>刪</button></td>";
        result += "</tr>"
    }
    result += '</table>';
    return result;
}

function supplierAddItem() {
    let form = document.getElementById('myForm');
    // product name, description, price
    let mydat = new FormData(form);
    // add supplierID to mydat
    mydat.append('supplierID', Cookies.get('userID'));
    let url = './supplier/supplierController.php?act=addItem';
    fetch(url, {
        method: 'POST',
        body: mydat,
    })
    .then(function (data) {
        supplierLoadProductList();
    })
}