<?php
session_start();
 include "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Coffee shop website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
        <nav class="navbar">
    <a href="index.php" class="nav-logo">
        <img src="image/logo.png" class="logo-img">
        <h2 class="logo-text">coffee shop</h2>
    </a> 

    <ul class="nav-menu">
        <li class="nav-item"><a href="product.php" class="nav-link">Product</a></li>
        <li class="nav-item"><a href="about.php" class="nav-link">About Us</a></li>
        <li class="nav-item"><a href="contactus.php" class="nav-link">Contact</a></li>

        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link">
    <img src="image/profile.jpg" class="nav-profile-img">
</a>
            </li>
        <?php else: ?>
            <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
            <li class="nav-item"><a href="register.php" class="nav-link">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
         <div class="contrainer">
            <h1>The menu</h1>
            <h2>cold drinks</h2>
            <div class="drink-row">
              <button class="side-arrow left" onclick="scrollLeft('.Iceddrink-list')"> ❮</button>
              <div class="Iceddrink-list">
               <?php
                    $result = $conn->query("SELECT * FROM products WHERE category='cold'");
                    while($row = $result->fetch_assoc()):
                ?>
                <div class="product-card"
                   onclick="openModal(
                    '<?php echo $row['name']; ?>',
                    'image/<?php echo $row['image_url']; ?>',
                    <?php echo $row['has_milk'] ? 'true' : 'false'; ?>,
                    <?php echo $row['price']; ?>,
                    <?php echo $row['id']; ?>) ">
 
                    <img src="image/<?php echo $row['image_url']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['calories']; ?> Cal</p>
                    <p class="price">Price: <?php echo $row['price']; ?> DA</p>

                </div>
                <?php endwhile; ?>
            
    
             </div>
             
             <button class="side-arrow right" onclick="scrollRight('.Iceddrink-list')">❯</button>

            </div>
            <h2>Hot drinks</h2>
            <div class="drink-row">
               <button class="side-arrow left" onclick="scrollLeft('.Hotdrink-list')"> ❮</button>
              <div class="Hotdrink-list">
               <?php
                $result = $conn->query("SELECT * FROM products WHERE category='hot'");
                while($row = $result->fetch_assoc()):
                ?>
                <div class="product-card"
                    onclick="openModal(
                    '<?php echo $row['name']; ?>',
                    'image/<?php echo $row['image_url']; ?>',
                    <?php echo $row['has_milk'] ? 'true' : 'false'; ?>,
                     <?php echo $row['price']; ?>,
                     <?php echo $row['id']; ?>)">
 
                    <img src="image/<?php echo $row['image_url']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['calories']; ?> Cal</p>
                    <p class="price">Price: <?php echo $row['price']; ?> DA</p>

                </div>
                <?php endwhile; ?>
                   
            </div>

          <button class="side-arrow right" onclick="scrollRight('.Hotdrink-list')">❯</button>

       </div>
     
            <h2>Sweets & breads</h2>
            <div class="drink-row">
               <button class="side-arrow left" onclick="scrollLeft('.Sweets-list')"> ❮</button>
               <div class="Sweets-list">
                <?php
                    $result = $conn->query("SELECT * FROM products WHERE category='sweets'");
                    while($row = $result->fetch_assoc()):
                ?>
               <div class="product-card"
                        date-id="<?php echo $row['id'];?>"
                    onclick="openModal(
                    '<?php echo $row['name']; ?>',
                   'image/<?php echo $row['image_url']; ?>',
                    <?php echo $row['has_milk'] ? 'true' : 'false'; ?>,
                         <?php echo $row['price']; ?>,
                        <?php echo $row['id']; ?>)">
 
                    <img src="image/<?php echo $row['image_url']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['calories']; ?> Cal</p>
                    <p class="price">Price: <?php echo $row['price']; ?> DA</p>

                </div>
                 <?php endwhile; ?>
               
               
                
               
               
               
                 
               
               
            </div>
            <button class="side-arrow right" onclick="scrollRight('.Sweets-list')">❯</button>
            </div>



       <div class="modal" id="productModal">
         <div class="modal-content">
            <span class="close" onclick="closeModal()">×</span>
            <img id="modalImg" class="modal-img">
            <h2 id="modelTitel" style="color: #fff;"></h2>
         <label>Size</label>
         <select>
             <option>Small</option>
              <option>Medium</option>
               <option>Large</option>
         </select>
         <div id="milkSection">
          <label>Milk</label>
         <select>
             <option>Whole milk</option>
              <option>Almond milk</option>
               <option>oat milk</option>
         </select>
         </div>


         <label>Sugar Level</label>
         <div class="sugar-contrainer">
            <input type="range" min="0" max="100" value="50" id="sugarRange">
            <div class="sugar-bar">
               <div class="sugar-fill" id="sugarFill"></div>
            </div>
         </div>
         <p id="sugarValue" class="sugar-text">sugar :50%</p>

         
         <label>Quantity</label>
         <div class="qty-box">
            <button onclick="changeQty(-1)">-</button>
            <span id="qtyValue" class="number">1</span>
             <button onclick="changeQty(1)">+</button>

         </div>
         <p id="modalPrice" class="price-text"></p>
      <button class="add-to-basket">ADD TO BASKET</button>   
      </div>

       </div>
       


       <div id="basket" class="basket">
         <span id="basketCount">0</span>
         <img src="image/basket.png">
         <div id="basketContent" class="basket-content">
            <h3> Basket</h3>
            <ul id="basketItems"></ul>
            <p id="basketTotal"></p>
            <button class="checkout-btn">Checkout</button>
         </div>
       </div>



       <script>
         const basketIcon=document.getElementById("basket");
         const basketContent=document.getElementById("basketContent");
         basketIcon.addEventListener("click",(e)=>{
            e.stopPropagation();
            basketContent.classList.toggle("show");
         });
         document.addEventListener("click",(e)=>{
            e.stopPropagation();
         });

         document.addEventListener("click",(e)=>{
           if(!basketIcon.contains(e.target)){
            basketContent.classList.remove("show");
           }
         });
            

       </script>


      <script>
          let qty =1;
          let currentPrice=0;
          let currentProductId = 0; 
          let basket = [];
         function openModal(title,imgSrc,hasMilk,price,productId){
            currentProductId = productId;
            const modal=document.getElementById("productModal");
            document.getElementById("modelTitel").textContent=title;
            const modalImg= document.getElementById("modalImg");
            modalImg.src=imgSrc;
            document.getElementById("qtyValue").textContent=1;
            modalImg.classList.remove("hot-size");

            const hotkeyword=["latte","hot","tea","espresso","chocolate","chai"];
            const isHot=hotkeyword.some(word => title.toLowerCase().includes(word));
         
            const drinkwords=["coffee","latte","tea","matcha","mocha","americano","hot","juice","milk","frappe","boba"];
            const isDrink =drinkwords.some(w =>title.toLowerCase().includes(w))
            document.querySelector(".sugar-contrainer").style.display=isDrink ? "block" :"none";
            document.getElementById("sugarValue").style.display=isDrink ? "block" :"none";
            document.getElementById("milkSection").style.display=hasMilk?"block":"none";
            if(isHot) modalImg.classList.add("hot-size");
            qty=1;
            document.getElementById("qtyValue").textContent=qty;
            currentPrice=price;
            updatePriceDisplay();
            modal.style.display="flex";
            
         
         }
         function closeModal(){
            document.getElementById("productModal").style.display="none";
         }
       const sugarRange=document.getElementById("sugarRange");
       const sugarValue=document.getElementById("sugarValue");
       const sugarFill=document.getElementById("sugarFill");

       sugarRange.addEventListener("input", () => {
        sugarValue.textContent=`sugar:${sugarRange.value}%`;
        sugarFill.style.width=sugarRange.value +"%";
         });


     </script>
<script>
   
   function changeQty(amount){
      qty +=amount;
      if(qty < 1) qty =1;
      document.getElementById("qtyValue").textContent =qty;
      updatePriceDisplay();
   }

   function updatePriceDisplay(){
      const totalPrice =qty*currentPrice;
      document.getElementById("modalPrice").textContent="Price: "+totalPrice+"DA";
   }
</script>
     <script>
      function scrollLeft(listClass){
         const list =document.querySelector(listClass);
         const cardWidth=list.querySelector('.product-card').offsetWidth+20;
         list.scrollLeft=Math.max(0,list.scrollLeft -cardWidth);
      }

      function scrollRight(listClass){
         const list =document.querySelector(listClass);
                  const cardWidth=list.querySelector('.product-card').offsetWidth+20;
         list.scrollLeft=Math.min(list.scrollWidth -list.clientWidth,list.scrollLeft + cardWidth);
      }
     </script>

   <script>
     
      
      document.querySelector(".add-to-basket").addEventListener("click", () =>{
         const title =document.getElementById("modelTitel").textContent;
         const totalPrice =qty *currentPrice;
         const existing=basket.find(item => item.title === title);
         if(existing){
            existing.qty +=qty;
            existing.totalPrice +=totalPrice;
         }else{
            basket.push({
                id: currentProductId,
               title :title,
               qty :qty,
               totalPrice :totalPrice
            });
         }
         updateBasketDisplay();
         
         closeModal();

      });

      function updateBasketDisplay(){
         const basketItems =document.getElementById("basketItems");
         basketItems.innerHTML="";

         let total =0;
         basket.forEach((item,index )=>{
            const li = document.createElement("li");
            const  nameSpan=document.createElement("span");
            //text.textContent=`${item.title}x${item.qty}`;
            nameSpan.textContent=item.title;
            nameSpan.classList.add("item-name");
            const qtySpan=document.createElement("span");
            qtySpan.textContent=item.qty;
            qtySpan.classList.add("item-qty");
            const removeBtn=document.createElement("button");
            removeBtn.textContent="×";
            removeBtn.classList.add("remove-btn");
            removeBtn.onclick=(e)=>{
               e.stopPropagation();
               basket.splice(index,1);
               updateBasketDisplay();
            };
            li.appendChild(nameSpan);
            li.appendChild(qtySpan);            
            li.appendChild(removeBtn);
            basketItems.appendChild(li)
            total +=item.totalPrice;
         });

      document.getElementById("basketTotal").textContent="Total: "+total+" DA";
      let count=0;
      basket.forEach(item=>{
         count+=item.qty;
      })
      document.getElementById("basketCount").textContent=count;

      }

     </script>


<script>
document.querySelector(".checkout-btn").addEventListener("click", () => {
    <?php if(!isset($_SESSION['user_id'])): ?>
        alert("Sorry, you must be logged in to complete the payment!");
        window.location.href = "login.php"; 
        return;
    <?php endif; ?>

    if(basket.length === 0){
        alert("Basket is empty!");
        return;
    }

    const total = basket.reduce((sum, item) => sum + item.totalPrice, 0);

   
    fetch('checkout.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({basket: basket, total: total})
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            alert(data.message);
            basket = []; 
            updateBasketDisplay(); 
             closeModal();
        } else {
            alert(data.message);
        }
    })
    .catch(err => console.error(err));
});
</script>
<script>
/* ===== Reorder basket loader ===== */
const savedBasket = localStorage.getItem("reorderBasket");

if (savedBasket) {
    basket = JSON.parse(savedBasket);
    updateBasketDisplay();
    localStorage.removeItem("reorderBasket");
}
</script>


<script src="https://cdn.botpress.cloud/webchat/v3.5/inject.js"></script>
<script src="https://files.bpcontent.cloud/2025/11/21/17/20251121172213-E7XTI0NW.js" defer></script>

</body>


</html>