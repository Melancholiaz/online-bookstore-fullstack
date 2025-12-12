// ==== ข้อมูลหนังสือ (ตัวอย่าง) ====
var books = [
  // philosophy
  { title:"ปรัชญาหนังสือหายาก", price:350, category:"philosophy", img:"img/BookType1-1.jpg" },
  { title:"ปรัชญาชีวิต", price:280, category:"philosophy", img:"img/BookType1-2.jpg" },
  { title:"ปรัชญาตะวันตก", price:450, category:"philosophy", img:"img/BookType1-3.jpg" },
  { title:"ปรัชญาตะวันออก", price:390, category:"philosophy", img:"img/BookType1-4.jpg" },
  { title:"จิตวิทยาและปรัชญา", price:320, category:"philosophy", img:"img/BookType1-5.jpg" },
  { title:"นักปราชญ์กรีกโบราณ", price:500, category:"philosophy", img:"img/BookType1-6.jpg" },
  { title:"ความหมายของชีวิต", price:350, category:"philosophy", img:"img/BookType1-7.jpg" },
  { title:"การโต้แย้งเชิงปรัชญา", price:410, category:"philosophy", img:"img/BookType1-8.jpg" },
  { title:"ศาสนาและปรัชญา", price:370, category:"philosophy", img:"img/BookType1-9.jpg" },
  { title:"นักคิดผู้ยิ่งใหญ่", price:480, category:"philosophy", img:"img/BookType1-10.jpg" },

  // education
  { title:"เขียนยังไงให้จำขึ้นใจ", price:220, category:"education", img:"img/BookType2-1.jpg" },
  { title:"การใช้กฎหมายเบื้องต้น", price:890, category:"education", img:"img/BookType2-2.jpg" },

  // science
  { title:"ฟิสิกส์เล่มแรก", price:420, category:"science", img:"img/BookType3-1.jpg" },

  // novel
  { title:"The Geometry of Pasta", price:450, category:"novel", img:"img/BookType4-1.jpg" },

  // kids
  { title:"การ์ตูนเด็กหรรษา", price:180, category:"kids", img:"img/BookType5-1.jpg" }
];

var bookList   = document.getElementById("book-list");
var filterBtns = document.querySelectorAll(".filter-btn");
var searchInput= document.getElementById("search");

// แสดงหนังสือ
function displayBooks(filter, search) {
  if (!filter) filter = "all";
  if (!search) search = "";
  bookList.innerHTML = "";

  for (var i=0; i<books.length; i++) {
    var b = books[i];
    if (filter !== "all" && b.category !== filter) continue;

    var card = document.createElement("div");
    card.className = "book-card";
    card.innerHTML =
      '<img src="'+b.img+'" alt="'+b.title+'">'+
      '<div class="content">'+
        '<div class="title">'+b.title+'</div>'+
        '<div class="price">฿'+b.price+'</div>'+
        '<button class="btn-add" onclick="addToCart(\''+b.title.replace(/'/g,"\\'")+'\', '+b.price+', \''+b.img+'\')">เพิ่มลงตะกร้า</button>'+
      '</div>';
    bookList.appendChild(card);
  }
}

// ฟิลเตอร์
for (var i=0;i<filterBtns.length;i++){
  filterBtns[i].addEventListener("click",(function(btn){
    return function(){ displayBooks(btn.getAttribute("data-category"), searchInput ? searchInput.value : ""); };
  })(filterBtns[i]));
}
if (searchInput) {
  searchInput.addEventListener("input", function(e){ displayBooks("all", e.target.value); });
}

displayBooks("all","");

// ==== ตะกร้า ====
var cart = [];

function addToCart(title, price, img) {
  for (var i=0;i<cart.length;i++){
    if (cart[i].title === title) { cart[i].qty++; updateCart(); return; }
  }
  cart.push({ title:title, price:price, img:img, qty:1 });
  updateCart();
}

function updateCart() {
  var items    = document.getElementById("cartItems");
  var totalDom = document.getElementById("cartTotal");
  var countDom = document.getElementById("cartCount");
  if (!items) return;

  items.innerHTML = "";
  var total = 0, count = 0;
  for (var i=0;i<cart.length;i++){
    var it = cart[i];
    total += it.price * it.qty;
    count += it.qty;

    var div = document.createElement("div");
    div.className = "cart-item";
    div.innerHTML =
      '<img src="'+it.img+'" alt="'+it.title+'">'+
      '<div class="info"><div>'+it.title+'</div><div>฿'+it.price+'</div></div>'+
      '<div class="qty">'+
        '<button onclick="changeQty('+i+',-1)">-</button>'+
        '<span>'+it.qty+'</span>'+
        '<button onclick="changeQty('+i+',1)">+</button>'+
      '</div>';
    items.appendChild(div);
  }
  totalDom.textContent = "฿" + total;
  countDom.textContent = count;

  var checkoutBtn = document.getElementById('checkoutBtn');
  if (checkoutBtn) checkoutBtn.disabled = cart.length===0;
}

function changeQty(index, delta){
  cart[index].qty += delta;
  if (cart[index].qty <= 0) cart.splice(index,1);
  updateCart();
}

// เปิด/ปิด cart
var cartPanel = document.getElementById("cartPanel");
var cartBtn   = document.getElementById("cartBtn");
var closeCart = document.getElementById("closeCart");

if (cartBtn)  cartBtn.addEventListener("click", function(){ cartPanel.className = "cart-panel active"; });
if (closeCart) closeCart.addEventListener("click", function(){ cartPanel.className = "cart-panel"; });

// ลิงก์หมวดใน footer
var footerLinks = document.querySelectorAll('.filter-link');
for (var i=0;i<footerLinks.length;i++){
  footerLinks[i].addEventListener('click', function(e){
    e.preventDefault();
    var cat = this.getAttribute('data-category') || 'all';
    displayBooks(cat, (searchInput?searchInput.value:''));
    var booksSec = document.querySelector('.books');
    if (booksSec && booksSec.scrollIntoView) booksSec.scrollIntoView({behavior:'smooth'});
  });
}

// ================== ปุ่มไปหน้า Checkout  ==================
var checkoutBtnDom = document.getElementById('checkoutBtn');
if (checkoutBtnDom) {
  checkoutBtnDom.addEventListener('click', function(){
    localStorage.setItem('checkoutCart', JSON.stringify(cart));
    if (typeof window.IS_LOGGED_IN !== 'undefined' && !window.IS_LOGGED_IN) {
      // 🔧 ไป login.php แล้วพากลับไป checkout.php
      window.location.href = 'login.php?next=checkout.php';

      return;
    }
    window.location.href = 'checkout.php';
  });
}
