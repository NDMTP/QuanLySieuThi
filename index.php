<!DOCTYPE html>
<html lang="en">
<?php
require 'head.php';
require 'connect.php';
require 'popup_themthanhcong.php';
?>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        position: relative;
    }

    /* Chat bubble styles */
    #chat-bubble {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #4CAF50;
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        text-align: center;
        line-height: 50px;
        cursor: pointer;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
        z-index: 1000;
    }

    /* Chat box styles */
    #chat-box {
        display: none; /* Mặc định là ẩn */
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 300px;
        max-height: 450px;
        border: 1px solid #ddd;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        background-color: white;
        border-radius: 10px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
    }

    #chat-box header {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        border-radius: 10px 10px 0 0;
        text-align: center;
        position: relative; /* Để chứa nút đóng */
    }

    #chat-box .close-btn {
        position: absolute;
        right: 10px;
        top: 5px;
        background-color: transparent;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
    }

    #chat-box .messages {
        padding: 10px;
        overflow-y: auto;
        height: 280px;
        border-bottom: 1px solid #ddd;
    }

    #chat-box footer {
        display: flex;
        align-items: center;
        padding: 10px;
        border-top: 1px solid #ddd;
        background-color: #f9f9f9;
    }

    #chat-box input {
        flex: 1;
        padding: 5px;
        border-radius: 5px;
        border: 1px solid #ddd;
        margin-right: 5px;
    }

    #chat-box button {
        padding: 5px 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    /* Suggestions styles */
    .suggestions {
        display: flex;
        flex-wrap: wrap;
        padding: 10px;
        background-color: #f9f9f9;
        border-top: 1px solid #ddd;
        justify-content: center;
    }

    .suggestion-button {
        margin-right: 5px;
        margin-bottom: 5px;
        padding: 5px 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
</style>

<body>
    <div id="chat-bubble">Chat</div>

    <div id="chat-box">
        <header>
            Chatbot Support
            <button class="close-btn" onclick="closeChatBox()">X</button> <!-- Nút X -->
        </header>
        <div class="messages">
            <!-- Messages will appear here -->
        </div>
        <div class="suggestions" id="suggestions-container"></div>
        <footer>
            <input type="text" id="message" placeholder="Nhập tin nhắn...">
            <button onclick="sendMessage()">Gửi</button>
        </footer>
    </div>

    <script>
        // Mặc định là ẩn
        var chatBox = document.getElementById('chat-box');
        chatBox.style.display = 'none';

        // Toggle chat box visibility khi nhấn vào chat-bubble
        document.getElementById('chat-bubble').addEventListener('click', function () {
            if (chatBox.style.display === 'none' || chatBox.style.display === '') {
                chatBox.style.display = 'block'; // Hiển thị
            } else {
                chatBox.style.display = 'none'; // Ẩn
            }
        });

        // Hàm đóng hộp thoại chat khi nhấn nút X
        function closeChatBox() {
            chatBox.style.display = 'none'; // Ẩn hộp thoại chat
        }

        // Function to send a message
        function sendMessage() {
            var message = document.getElementById('message').value;
            if (message.trim() !== "") {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'chatbot.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        var messagesDiv = document.querySelector('.messages');
                        var suggestionsContainer = document.getElementById('suggestions-container');

                        // Clear previous suggestions
                        suggestionsContainer.innerHTML = '';

                        // Add user message
                        var userMessage = document.createElement('div');
                        userMessage.textContent = "Bạn: " + message;
                        messagesDiv.appendChild(userMessage);

                        // Add bot response
                        var botMessage = document.createElement('div');
                        botMessage.textContent = "Bot: " + response.message;
                        messagesDiv.appendChild(botMessage);

                        // Add suggestions
                        response.suggestions.forEach(function (suggestion) {
                            var suggestionButton = document.createElement('button');
                            suggestionButton.textContent = suggestion;
                            suggestionButton.className = 'suggestion-button';
                            suggestionButton.onclick = function () {
                                document.getElementById('message').value = suggestion;
                                sendMessage();
                            };
                            suggestionsContainer.appendChild(suggestionButton);
                        });

                        // Clear the message input after sending
                        document.getElementById('message').value = "";
                    }
                };

                xhr.send('message=' + encodeURIComponent(message));
            }
        }

        // Event listener for Enter key in the input field
        document.getElementById('message').addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                sendMessage();
            }
        });
    </script>
</body>
</html>


  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <defs>
      <symbol xmlns="http://www.w3.org/2000/svg" id="link" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M12 19a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0-4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm-5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm7-12h-1V2a1 1 0 0 0-2 0v1H8V2a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3Zm1 17a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9h16Zm0-11H4V6a1 1 0 0 1 1-1h1v1a1 1 0 0 0 2 0V5h8v1a1 1 0 0 0 2 0V5h1a1 1 0 0 1 1 1ZM7 15a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0 4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="arrow-right" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="category" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M19 5.5h-6.28l-.32-1a3 3 0 0 0-2.84-2H5a3 3 0 0 0-3 3v13a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-10a3 3 0 0 0-3-3Zm1 13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-13a1 1 0 0 1 1-1h4.56a1 1 0 0 1 .95.68l.54 1.64a1 1 0 0 0 .95.68h7a1 1 0 0 1 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="calendar" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M19 4h-2V3a1 1 0 0 0-2 0v1H9V3a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7h16Zm0-9H4V7a1 1 0 0 1 1-1h2v1a1 1 0 0 0 2 0V6h6v1a1 1 0 0 0 2 0V6h2a1 1 0 0 1 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="heart" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M20.16 4.61A6.27 6.27 0 0 0 12 4a6.27 6.27 0 0 0-8.16 9.48l7.45 7.45a1 1 0 0 0 1.42 0l7.45-7.45a6.27 6.27 0 0 0 0-8.87Zm-1.41 7.46L12 18.81l-6.75-6.74a4.28 4.28 0 0 1 3-7.3a4.25 4.25 0 0 1 3 1.25a1 1 0 0 0 1.42 0a4.27 4.27 0 0 1 6 6.05Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="plus" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M19 11h-6V5a1 1 0 0 0-2 0v6H5a1 1 0 0 0 0 2h6v6a1 1 0 0 0 2 0v-6h6a1 1 0 0 0 0-2Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="minus" viewBox="0 0 24 24">
        <path fill="currentColor" d="M19 11H5a1 1 0 0 0 0 2h14a1 1 0 0 0 0-2Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="cart" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M8.5 19a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8.5 19ZM19 16H7a1 1 0 0 1 0-2h8.491a3.013 3.013 0 0 0 2.885-2.176l1.585-5.55A1 1 0 0 0 19 5H6.74a3.007 3.007 0 0 0-2.82-2H3a1 1 0 0 0 0 2h.921a1.005 1.005 0 0 1 .962.725l.155.545v.005l1.641 5.742A3 3 0 0 0 7 18h12a1 1 0 0 0 0-2Zm-1.326-9l-1.22 4.274a1.005 1.005 0 0 1-.963.726H8.754l-.255-.892L7.326 7ZM16.5 19a1.5 1.5 0 1 0 1.5 1.5a1.5 1.5 0 0 0-1.5-1.5Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="check" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M18.71 7.21a1 1 0 0 0-1.42 0l-7.45 7.46l-3.13-3.14A1 1 0 1 0 5.29 13l3.84 3.84a1 1 0 0 0 1.42 0l8.16-8.16a1 1 0 0 0 0-1.47Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="trash" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M10 18a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1ZM20 6h-4V5a3 3 0 0 0-3-3h-2a3 3 0 0 0-3 3v1H4a1 1 0 0 0 0 2h1v11a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8h1a1 1 0 0 0 0-2ZM10 5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h-4Zm7 14a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V8h10Zm-3-1a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="star-outline" viewBox="0 0 15 15">
        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
          d="M7.5 9.804L5.337 11l.413-2.533L4 6.674l2.418-.37L7.5 4l1.082 2.304l2.418.37l-1.75 1.793L9.663 11L7.5 9.804Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="star-solid" viewBox="0 0 15 15">
        <path fill="currentColor"
          d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="search" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="user" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M15.71 12.71a6 6 0 1 0-7.42 0a10 10 0 0 0-6.22 8.18a1 1 0 0 0 2 .22a8 8 0 0 1 15.9 0a1 1 0 0 0 1 .89h.11a1 1 0 0 0 .88-1.1a10 10 0 0 0-6.25-8.19ZM12 12a4 4 0 1 1 4-4a4 4 0 0 1-4 4Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="close" viewBox="0 0 15 15">
        <path fill="currentColor"
          d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z" />
      </symbol>
    </defs>
  </svg>

  <!-- <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div> -->

  <?php
  include "header.php";
  ?>
  <?php
  if (isset($_SESSION['email'])) {
    ?>
    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasCart" aria-labelledby="My Cart">
      <div class="offcanvas-header justify-content-center">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <div class="order-md-last">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-primary">Giỏ hàng của bạn</span>
            <span class="badge bg-primary rounded-pill">
              <?php echo $_SESSION['slsp'] ?>
            </span>
          </h4>
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-sm">
              <?php
              if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                  $sql = "SELECT * FROM sanpham WHERE MASP = '{$item['id']}'";
                  $result = $conn->query($sql);
                  $row = $result->fetch_assoc();
                  $string = $row['MASP'];
                  $masp = preg_replace('/[0-9]/', '', $string);
                  ?>
                  <div>
                    <h6 class="my-0"><a href="#" class="product-name">
                        <?php echo $row['TENSP'] ?>
                      </a></h6>
                    <small class="text-body-secondary">
                      <?php echo $row['MOTA'] ?>
                    </small>
                  </div>
                  <span class="text-body-secondary">
                    <?php echo number_format($row['DONGIABANSP']) ?>
                  </span>
                </li>
                <div class="qty">
                  <label for="cart[id123][qty]">Số lượng:</label>
                  <input type="number" class="input-qty" name="cart[id123][qty]" id="cart[id123][qty]"
                    value="<?php echo $item['quant'] ?>" disabled>
                </div>
                <?php
                }
                ?>
              <button class="w-100 btn btn-primary btn-lg" id="checkoutButton" type="button">Tiếp tục thanh toán</button>
              <?php
              } else {
                echo '<p style="margin-top: 15px; font-size: 18px !important">Không có sản phẩm nào trong giỏ hàng</p>';
              }
              ?>
        </div>
      </div>
    </div>

    <script>
      document.getElementById("checkoutButton").addEventListener("click", function () {
        window.location.href = "giohang.php";
      });
    </script>


    </div>
    </div>
    <?php
  }
  ?>

  <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasSearch"
    aria-labelledby="Search">
    <div class="offcanvas-header justify-content-center">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-primary">Search</span>
        </h4>
        <form role="search" action="index.php" method="get" class="d-flex mt-3 gap-0">
          <input class="form-control rounded-start rounded-0 bg-light" type="email"
            placeholder="What are you looking for?" aria-label="What are you looking for?">
          <button class="btn btn-dark rounded-end rounded-0" type="submit">Search</button>
        </form>
      </div>
    </div>
  </div>



  <section class="py-3"
    style="background-image: url('images/background-pattern.jpg');background-repeat: no-repeat;background-size: cover;">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">

          <div class="banner-blocks">

            <div class="banner-ad large bg-info block-1">

              <div class="swiper main-swiper">
                <div class="swiper-wrapper">

                  <div class="swiper-slide">
                    <div class="row banner-content p-5">
                      <div class="content-wrapper col-md-7">
                        <div class="categories my-3">100% Tự nhiên</div>
                        <h3 class="display-4">Nước ép cam nguyên chất</h3>
                        <p>Với sự tin tưởng của khách hàng, chúng tôi đã ra mắt một sản phẩm mới 100% từ thiên nhiên</p>
                        <a href="#" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1 px-4 py-3 mt-3">Đặt
                          hàng ngay</a>
                      </div>
                      <div class="img-wrapper col-md-5">
                        <img src="images/product-thumb-1.png" class="img-fluid">
                      </div>
                    </div>
                  </div>

                  <div class="swiper-slide">
                    <div class="row banner-content p-5">
                      <div class="content-wrapper col-md-7">
                        <div class="categories mb-3 pb-3">100% natural</div>
                        <h3 class="banner-title">Fresh Smoothie & Summer Juice</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dignissim massa diam elementum.</p>
                        <a href="#" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">Shop
                          Collection</a>
                      </div>
                      <div class="img-wrapper col-md-5">
                        <img src="images/product-thumb-1.png" class="img-fluid">
                      </div>
                    </div>
                  </div>

                  <div class="swiper-slide">
                    <div class="row banner-content p-5">
                      <div class="content-wrapper col-md-7">
                        <div class="categories mb-3 pb-3">100% natural</div>
                        <h3 class="banner-title">Heinz Tomato Ketchup</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dignissim massa diam elementum.</p>
                        <a href="#" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">Shop
                          Collection</a>
                      </div>
                      <div class="img-wrapper col-md-5">
                        <img src="images/product-thumb-2.png" class="img-fluid">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="swiper-pagination"></div>

              </div>
            </div>

            <div class="banner-ad bg-success-subtle block-2"
              style="background:url('images/ad-image-1.png') no-repeat;background-position: right bottom">
              <div class="row banner-content p-5">

                <div class="content-wrapper col-md-7">
                  <div class="categories mb-3 pb-3">Giảm giá 20%</div>
                  <h3 class="banner-title">Trái cây và rau củ</h3>
                  <a href="sanpham.php?loai=01" class="d-flex align-items-center nav-link">Xem chi tiết <svg width="24"
                      height="24">
                      <use xlink:href="#arrow-right"></use>
                    </svg></a>
                </div>

              </div>
            </div>

            <div class="banner-ad bg-danger block-3"
              style="background:url('images/ad-image-2.png') no-repeat;background-position: right bottom">
              <div class="row banner-content p-5">

                <div class="content-wrapper col-md-7">
                  <div class="categories mb-3 pb-3">Giảm giá 15%</div>
                  <h3 class="item-title">Bánh mì</h3>
                  <a href="sanpham.php" class="d-flex align-items-center nav-link">Xem chi tiết <svg width="24"
                      height="24">
                      <use xlink:href="#arrow-right"></use>
                    </svg></a>
                </div>

              </div>
            </div>

          </div>
          <!-- / Banner Blocks -->

        </div>
      </div>
    </div>
  </section>

  <section class="py-5 overflow-hidden">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">

          <div class="section-header d-flex flex-wrap justify-content-between mb-5">
            <h2 class="section-title">Danh mục sản phẩm</h2>

            <div class="d-flex align-items-center">
              <a href="#" class="btn-link text-decoration-none">Xem tất cả danh mục →</a>
              <div class="swiper-buttons">
                <button class="swiper-prev category-carousel-prev btn btn-yellow">❮</button>
                <button class="swiper-next category-carousel-next btn btn-yellow">❯</button>
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="row">
        <div class="col-md-12">

          <div class="category-carousel swiper">
            <div class="swiper-wrapper">
              <a href="sanpham.php?loai=01" class="nav-link category-item swiper-slide">
                <i class="fa-regular fa-lemon fa-2xl" style="color: #FFD43B;"></i>
                <h3 class="category-title">Trái cây</h3>
              </a>
              <a href="sanpham.php?loai=02" class="nav-link category-item swiper-slide">
                <i class="fa-solid fa-fish-fins fa-2xl" style="color: #24b8b4;"></i>
                <h3 class="category-title">Hải sản</h3>
              </a>
              <a href="sanpham.php?loai=03" class="nav-link category-item swiper-slide">
                <img src="images/icon-animal-products-drumsticks.png" alt="Category Thumbnail">
                <h3 class="category-title">Thịt</h3>
              </a>
              <a href="sanpham.php?loai=04" class="nav-link category-item swiper-slide">
                <i class="fa-solid fa-egg fa-2xl" style="color: #ffa742;"></i>
                <h3 class="category-title">Trứng</h3>
              </a>
              <a href="sanpham.php?loai=05" class="nav-link category-item swiper-slide">
                <i class="fa-solid fa-carrot fa-2xl" style="color: #5ed756;"></i>
                <h3 class="category-title">Rau</h3>
              </a>

              <a href="sanpham.php?loai=06" class="nav-link category-item swiper-slide">
                <i class="fa-solid fa-paw fa-2xl" style="color: #fd0d0d;"></i>
                <h3 class="category-title">Đồ cho thú cưng</h3>
              </a>
              <a href="sanpham.php?loai=07" class="nav-link category-item swiper-slide">
                <i class="fa-solid fa-bowl-rice fa-2xl" style="color: #ffdd00;"></i>
                <h3 class="category-title">Mì</h3>
              </a>
              <a href="sanpham.php?loai=08" class="nav-link category-item swiper-slide">
                <i class="fa-solid fa-utensils fa-2xl"></i>
                <h3 class="category-title">Vật dụng gia đình</h3>
              </a>
              <a href="sanpham.php?loai=09" class="nav-link category-item swiper-slide">
                <i class="fa-solid fa-bottle-water fa-2xl" style="color: #B197FC;"></i>
                <h3 class="category-title">Nước uống</h3>
              </a>

            </div>
          </div>

        </div>
      </div>
    </div>
  </section>


  <section class="py-5 overflow-hidden">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">

          <div class="section-header d-flex flex-wrap flex-wrap justify-content-between mb-5">

            <h2 class="section-title">Thương hiệu mới</h2>

            <div class="d-flex align-items-center">
              <a href="#" class="btn-link text-decoration-none">Xem thêm →</a>
              <div class="swiper-buttons">
                <button class="swiper-prev brand-carousel-prev btn btn-yellow">❮</button>
                <button class="swiper-next brand-carousel-next btn btn-yellow">❯</button>
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="row">
        <div class="col-md-12">

          <div class="brand-carousel swiper">
            <div class="swiper-wrapper">

              <div class="swiper-slide">
                <div class="card mb-3 p-3 rounded-4 shadow border-0">
                  <div class="row g-0">
                    <div class="col-md-4">
                      <img src="./images/milo.jpg" class="img-fluid rounded" alt="Card title">
                    </div>
                    <div class="col-md-8">
                      <div class="card-body py-0">
                        <p class="text-muted mb-0">Milo</p>
                        <h5 class="card-title">Thức uống lúa mạch từ thiên nhiên</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="card mb-3 p-3 rounded-4 shadow border-0">
                  <div class="row g-0">
                    <div class="col-md-4">
                      <img src="./images/omachi.jpg" class="img-fluid rounded" alt="Card title">
                    </div>
                    <div class="col-md-8">
                      <div class="card-body py-0">
                        <p class="text-muted mb-0">Omachi</p>
                        <h5 class="card-title">Sức hấp dẫn không thể chối từ</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="card mb-3 p-3 rounded-4 shadow border-0">
                  <div class="row g-0">
                    <div class="col-md-4">
                      <img src="./images/kokomi.jpg" class="img-fluid rounded" alt="Card title">
                    </div>
                    <div class="col-md-8">
                      <div class="card-body py-0">
                        <p class="text-muted mb-0">Kokomi</p>
                        <h5 class="card-title">Trong dai ngoài giòn</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="card mb-3 p-3 rounded-4 shadow border-0">
                  <div class="row g-0">
                    <div class="col-md-4">
                      <img src="./images/knorr.jpg" class="img-fluid rounded" alt="Card title">
                    </div>
                    <div class="col-md-8">
                      <div class="card-body py-0">
                        <p class="text-muted mb-0">Knoor</p>
                        <h5 class="card-title">Ngon từ thịt ngọt từ xương</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="card mb-3 p-3 rounded-4 shadow border-0">
                  <div class="row g-0">
                    <div class="col-md-4">
                      <img src="images/product-thumb-11.jpg" class="img-fluid rounded" alt="Card title">
                    </div>
                    <div class="col-md-8">
                      <div class="card-body py-0">
                        <p class="text-muted mb-0">Amber Jar</p>
                        <h5 class="card-title">Honey best nectar you wish to get</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="card mb-3 p-3 rounded-4 shadow border-0">
                  <div class="row g-0">
                    <div class="col-md-4">
                      <img src="images/product-thumb-12.jpg" class="img-fluid rounded" alt="Card title">
                    </div>
                    <div class="col-md-8">
                      <div class="card-body py-0">
                        <p class="text-muted mb-0">Amber Jar</p>
                        <h5 class="card-title">Honey best nectar you wish to get</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
  </section>


  <section class="py-5">
    <div class="container-fluid">

      <div class="row">
        <div class="col-md-12">

          <div class="bootstrap-tabs product-tabs">
            <div class="tabs-header d-flex justify-content-between border-bottom my-5">
              <h3>Sản phẩm nổi bật</h3>
              <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a href="#" class="nav-link text-uppercase fs-6 active" id="nav-all-tab" data-bs-toggle="tab"
                    data-bs-target="#nav-all">Tất cả</a>
                  <a href="#" class="nav-link text-uppercase fs-6" id="nav-fruits-tab" data-bs-toggle="tab"
                    data-bs-target="#nav-fruits">Đồ cho thú cưng</a>
                  <a href="#" class="nav-link text-uppercase fs-6" id="nav-juices-tab" data-bs-toggle="tab"
                    data-bs-target="#nav-juices">Thịt</a>
                </div>
              </nav>
            </div>
            <div class="tab-content" id="nav-tabContent">
              <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">

                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

                  <?php

                  // Số sản phẩm trên mỗi trang
                  $productsPerPage = 10;

                  // Xác định trang hiện tại từ biến GET
                  $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

                  // Truy vấn lấy dữ liệu sản phẩm từ cơ sở dữ liệu
                  $offset = ($current_page - 1) * $productsPerPage;

                  $sql = " WHERE MALOAI ='01'";



                  if (isset($_GET['loai']) && $_GET['loai'] != "all") {
                    $sql = $sql . " AND MALOAI = " . $_GET['loai'];
                  }
                  if (isset($_GET['gia'])) {
                    switch ($_GET['gia']) {
                      case 'gia1':
                        $sql = $sql . " AND DONGIABANSP BETWEEN 0 AND 10000";
                        break;
                      case 'gia2':
                        $sql = $sql . " AND DONGIABANSP BETWEEN 10000 AND 20000";
                        break;
                      case 'gia3':
                        $sql = $sql . " AND DONGIABANSP BETWEEN 20000 AND 50000";
                        break;
                      case 'gia4':
                        $sql = $sql . " AND DONGIABANSP BETWEEN 50000 AND 100000";
                        break;
                      case 'gia5':
                        $sql = $sql . " AND DONGIABANSP >  100000";
                        break;
                      default:
                        break;
                    }

                  }




                  if (isset($_GET['search'])) {
                    $s = $_GET['search'];
                  } else
                    $s = '';
                  $sql .= " AND TENSP like '%" . $s . "%'";

                  $sql = $sql . " LIMIT $offset, $productsPerPage";

                  $query = "SELECT * FROM sanpham " . $sql;

                  $result = $conn->query($query);
                  if ($result->num_rows > 0) {
                    $result = $conn->query($query);
                    $result_all = $result->fetch_all(MYSQLI_ASSOC);
                    foreach ($result_all as $row) {
                      $string = $row['MASP'];
                      // Loại bỏ các kí tự số khỏi chuỗi
                      $masp = preg_replace('/[0-9]/', '', $string);
                      ?>
                      <div class="product-item">
                        <span class="badge bg-success position-absolute m-3">-30%</span>
                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                            <use xlink:href="#heart"></use>
                          </svg></a>
                        <figure>
                          <a href="single-product.php?id=<?php echo $row['MASP'] ?>" title="Product Title">
                            <img src="images/<?php echo $masp ?>/<?php echo $row['LINKANH'] ?>" alt="dd" width="270"
                              height="270" class="tab-image">
                          </a>
                        </figure>
                        <h3>
                          <?php echo $row['TENSP'] ?>
                        </h3>

                        <span class="price">
                          <?php echo number_format($row['DONGIABANSP']) ?> đ
                        </span>
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="input-group product-qty">
                            <span class="input-group-btn">
                              <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                <svg width="16" height="16">
                                  <use xlink:href="#minus"></use>
                                </svg>
                              </button>
                            </span>
                            <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                            <span class="input-group-btn">
                              <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                <svg width="16" height="16">
                                  <use xlink:href="#plus"></use>
                                </svg>
                              </button>
                            </span>
                          </div>
                          <a href="themvaogiohang.php?sb_cate=&pdid=<?php echo $row['MASP'] ?>&qty12554=1"
                            class="nav-link">Thêm
                            vào giỏ<svg width="18" height="18">
                              <use xlink:href="#cart"></use>
                            </svg></a>
                        </div>
                      </div>
                      <?php
                    }
                  } else {
                    echo "Không tìm thấy sản phẩm phù hợp";
                  }
                  ?>



                </div>
                <!-- / product-grid -->

              </div>

              <div class="tab-pane fade" id="nav-fruits" role="tabpanel" aria-labelledby="nav-fruits-tab">
                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

                  <?php

                  // Số sản phẩm trên mỗi trang
                  $productsPerPage = 10;

                  // Xác định trang hiện tại từ biến GET
                  $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

                  // Truy vấn lấy dữ liệu sản phẩm từ cơ sở dữ liệu
                  $offset = ($current_page - 1) * $productsPerPage;

                  $sql = " WHERE MALOAI ='06'";



                  if (isset($_GET['loai']) && $_GET['loai'] != "all") {
                    $sql = $sql . " AND MALOAI = " . $_GET['loai'];
                  }
                  if (isset($_GET['gia'])) {
                    switch ($_GET['gia']) {
                      case 'gia1':
                        $sql = $sql . " AND DONGIABANSP BETWEEN 0 AND 10000";
                        break;
                      case 'gia2':
                        $sql = $sql . " AND DONGIABANSP BETWEEN 10000 AND 20000";
                        break;
                      case 'gia3':
                        $sql = $sql . " AND DONGIABANSP BETWEEN 20000 AND 50000";
                        break;
                      case 'gia4':
                        $sql = $sql . " AND DONGIABANSP BETWEEN 50000 AND 100000";
                        break;
                      case 'gia5':
                        $sql = $sql . " AND DONGIABANSP >  100000";
                        break;
                      default:
                        break;
                    }

                  }




                  if (isset($_GET['search'])) {
                    $s = $_GET['search'];
                  } else
                    $s = '';
                  $sql .= " AND TENSP like '%" . $s . "%'";

                  $sql = $sql . " LIMIT $offset, $productsPerPage";

                  $query = "SELECT * FROM sanpham " . $sql;

                  $result = $conn->query($query);
                  if ($result->num_rows > 0) {
                    $result = $conn->query($query);
                    $result_all = $result->fetch_all(MYSQLI_ASSOC);
                    foreach ($result_all as $row) {
                      $string = $row['MASP'];
                      // Loại bỏ các kí tự số khỏi chuỗi
                      $masp = preg_replace('/[0-9]/', '', $string);
                      ?>
                      <div class="product-item">
                        <span class="badge bg-success position-absolute m-3">-30%</span>
                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                            <use xlink:href="#heart"></use>
                          </svg></a>
                        <figure>
                          <a href="single-product.php?id=<?php echo $row['MASP'] ?>" title="Product Title">
                            <img src="images/<?php echo $masp ?>/<?php echo $row['LINKANH'] ?>" alt="dd" width="270"
                              height="270" class="tab-image">
                          </a>
                        </figure>
                        <h3>
                          <?php echo $row['TENSP'] ?>
                        </h3>

                        <span class="price">
                          <?php echo number_format($row['DONGIABANSP']) ?> đ
                        </span>
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="input-group product-qty">
                            <span class="input-group-btn">
                              <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                <svg width="16" height="16">
                                  <use xlink:href="#minus"></use>
                                </svg>
                              </button>
                            </span>
                            <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                            <span class="input-group-btn">
                              <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                <svg width="16" height="16">
                                  <use xlink:href="#plus"></use>
                                </svg>
                              </button>
                            </span>
                          </div>
                          <a href="themvaogiohang.php?sb_cate=&pdid=<?php echo $row['MASP'] ?>&qty12554=1"
                            class="nav-link">Thêm
                            vào giỏ<svg width="18" height="18">
                              <use xlink:href="#cart"></use>
                            </svg></a>
                        </div>
                      </div>
                      <?php
                    }
                  } else {
                    echo "Không tìm thấy sản phẩm phù hợp";
                  }
                  ?>



                </div>
                <!-- / product-grid -->

              </div>
              <div class="tab-pane fade" id="nav-juices" role="tabpanel" aria-labelledby="nav-juices-tab">

                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

                  <?php

                  // Số sản phẩm trên mỗi trang
                  $productsPerPage = 10;

                  // Xác định trang hiện tại từ biến GET
                  $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

                  // Truy vấn lấy dữ liệu sản phẩm từ cơ sở dữ liệu
                  $offset = ($current_page - 1) * $productsPerPage;

                  $sql = " WHERE MALOAI ='03'";



                  if (isset($_GET['loai']) && $_GET['loai'] != "all") {
                    $sql = $sql . " AND MALOAI = " . $_GET['loai'];
                  }
                  if (isset($_GET['gia'])) {
                    switch ($_GET['gia']) {
                      case 'gia1':
                        $sql = $sql . " AND DONGIABANSP BETWEEN 0 AND 10000";
                        break;
                      case 'gia2':
                        $sql = $sql . " AND DONGIABANSP BETWEEN 10000 AND 20000";
                        break;
                      case 'gia3':
                        $sql = $sql . " AND DONGIABANSP BETWEEN 20000 AND 50000";
                        break;
                      case 'gia4':
                        $sql = $sql . " AND DONGIABANSP BETWEEN 50000 AND 100000";
                        break;
                      case 'gia5':
                        $sql = $sql . " AND DONGIABANSP >  100000";
                        break;
                      default:
                        break;
                    }

                  }




                  if (isset($_GET['search'])) {
                    $s = $_GET['search'];
                  } else
                    $s = '';
                  $sql .= " AND TENSP like '%" . $s . "%'";

                  $sql = $sql . " LIMIT $offset, $productsPerPage";

                  $query = "SELECT * FROM sanpham " . $sql;

                  $result = $conn->query($query);
                  if ($result->num_rows > 0) {
                    $result = $conn->query($query);
                    $result_all = $result->fetch_all(MYSQLI_ASSOC);
                    foreach ($result_all as $row) {
                      $string = $row['MASP'];
                      // Loại bỏ các kí tự số khỏi chuỗi
                      $masp = preg_replace('/[0-9]/', '', $string);
                      ?>
                      <div class="product-item">
                        <span class="badge bg-success position-absolute m-3">-30%</span>
                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                            <use xlink:href="#heart"></use>
                          </svg></a>
                        <figure>
                          <a href="single-product.php?id=<?php echo $row['MASP'] ?>" title="Product Title">
                            <img src="images/<?php echo $masp ?>/<?php echo $row['LINKANH'] ?>" alt="dd" width="270"
                              height="270" class="tab-image">
                          </a>
                        </figure>
                        <h3>
                          <?php echo $row['TENSP'] ?>
                        </h3>

                        <span class="price">
                          <?php echo number_format($row['DONGIABANSP']) ?> đ
                        </span>
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="input-group product-qty">
                            <span class="input-group-btn">
                              <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                <svg width="16" height="16">
                                  <use xlink:href="#minus"></use>
                                </svg>
                              </button>
                            </span>
                            <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                            <span class="input-group-btn">
                              <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                <svg width="16" height="16">
                                  <use xlink:href="#plus"></use>
                                </svg>
                              </button>
                            </span>
                          </div>
                          <a href="themvaogiohang.php?sb_cate=&pdid=<?php echo $row['MASP'] ?>&qty12554=1"
                            class="nav-link">Thêm
                            vào giỏ<svg width="18" height="18">
                              <use xlink:href="#cart"></use>
                            </svg></a>
                        </div>
                      </div>
                      <?php
                    }
                  } else {
                    echo "Không tìm thấy sản phẩm phù hợp";
                  }
                  ?>



                </div>
                <!-- / product-grid -->

              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container-fluid">
      <div class="row">

        <div class="col-md-6">
          <div class="banner-ad bg-danger mb-3"
            style="background: url('images/ad-image-3.png');background-repeat: no-repeat;background-position: right bottom;">
            <div class="banner-content p-5">

              <div class="categories text-primary fs-3 fw-bold">Giảm giá lên tới 25%</div>
              <h3 class="banner-title">Socola đen</h3>
              <p>Socola ngon và ngọt, chất lượng cao đến từ Pháp.</p>
              <a href="#" class="btn btn-dark text-uppercase">Đặt hàng ngay</a>

            </div>

          </div>
        </div>
        <div class="col-md-6">
          <div class="banner-ad bg-info"
            style="background: url('images/ad-image-4.png');background-repeat: no-repeat;background-position: right bottom;">
            <div class="banner-content p-5">

              <div class="categories text-primary fs-3 fw-bold">Giảm giá lên tới 15%</div>
              <h3 class="banner-title">Kem tươi</h3>
              <p>Một món ăn tươi ngon giúp xua tan cái nóng vào mùa hè</p>
              <a href="#" class="btn btn-dark text-uppercase">Đặt hàng ngay</a>

            </div>

          </div>
        </div>

      </div>
    </div>
  </section>

  <section class="py-5 overflow-hidden">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">

          <div class="section-header d-flex flex-wrap justify-content-between my-5">

            <h2 class="section-title">Các sản phẩm bán chạy nhất</h2>

            <div class="d-flex align-items-center">
              <a href="#" class="btn-link text-decoration-none">Xem tất cả →</a>
              <div class="swiper-buttons">
                <button class="swiper-prev products-carousel-prev btn btn-primary">❮</button>
                <button class="swiper-next products-carousel-next btn btn-primary">❯</button>
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="row">
        <div class="col-md-12">

          <div class="products-carousel swiper">
            <div class="swiper-wrapper">

              <div class="product-item swiper-slide">
                <span class="badge bg-success position-absolute m-3">-15%</span>
                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                    <use xlink:href="#heart"></use>
                  </svg></a>
                <figure>
                  <a href="single-product.php" title="Product Title">
                    <img src="images/thumb-tomatoes.png" class="tab-image">
                  </a>
                </figure>
                <h3>Cà chua</h3>
                <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                    <use xlink:href="#star-solid"></use>
                  </svg> 4.5</span>
                <span class="price">18.000</span>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="input-group product-qty">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                        <svg width="16" height="16">
                          <use xlink:href="#minus"></use>
                        </svg>
                      </button>
                    </span>
                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                        <svg width="16" height="16">
                          <use xlink:href="#plus"></use>
                        </svg>
                      </button>
                    </span>
                  </div>
                  <a href="#" class="nav-link">Thêm vào giỏ <iconify-icon icon="uil:shopping-cart"></a>
                </div>
              </div>

              <div class="product-item swiper-slide">
                <span class="badge bg-success position-absolute m-3">-15%</span>
                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                    <use xlink:href="#heart"></use>
                  </svg></a>
                <figure>
                  <a href="single-product.php" title="Product Title">
                    <img src="images/thumb-tomatoketchup.png" class="tab-image">
                  </a>
                </figure>
                <h3>Nước ép cà chua</h3>
                <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                    <use xlink:href="#star-solid"></use>
                  </svg> 4.5</span>
                <span class="price">18.000</span>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="input-group product-qty">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                        <svg width="16" height="16">
                          <use xlink:href="#minus"></use>
                        </svg>
                      </button>
                    </span>
                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                        <svg width="16" height="16">
                          <use xlink:href="#plus"></use>
                        </svg>
                      </button>
                    </span>
                  </div>
                  <a href="#" class="nav-link">Thêm vào giỏ <iconify-icon icon="uil:shopping-cart"></a>
                </div>
              </div>

              <div class="product-item swiper-slide">
                <span class="badge bg-success position-absolute m-3">-15%</span>
                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                    <use xlink:href="#heart"></use>
                  </svg></a>
                <figure>
                  <a href="single-product.php" title="Product Title">
                    <img src="images/thumb-bananas.png" class="tab-image">
                  </a>
                </figure>
                <h3>Chuối</h3>
                <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                    <use xlink:href="#star-solid"></use>
                  </svg> 4.5</span>
                <span class="price">18.000đ</span>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="input-group product-qty">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                        <svg width="16" height="16">
                          <use xlink:href="#minus"></use>
                        </svg>
                      </button>
                    </span>
                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                        <svg width="16" height="16">
                          <use xlink:href="#plus"></use>
                        </svg>
                      </button>
                    </span>
                  </div>
                  <a href="#" class="nav-link">Thêm vào giỏ <iconify-icon icon="uil:shopping-cart"></a>
                </div>
              </div>

              <div class="product-item swiper-slide">
                <span class="badge bg-success position-absolute m-3">-15%</span>
                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                    <use xlink:href="#heart"></use>
                  </svg></a>
                <figure>
                  <a href="single-product.php" title="Product Title">
                    <img src="images/thumb-bananas.png" class="tab-image">
                  </a>
                </figure>
                <h3>Chuối</h3>
                <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                    <use xlink:href="#star-solid"></use>
                  </svg> 4.5</span>
                <span class="price">18.000đ</span>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="input-group product-qty">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                        <svg width="16" height="16">
                          <use xlink:href="#minus"></use>
                        </svg>
                      </button>
                    </span>
                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                        <svg width="16" height="16">
                          <use xlink:href="#plus"></use>
                        </svg>
                      </button>
                    </span>
                  </div>
                  <a href="#" class="nav-link">Thêm vào giỏ <iconify-icon icon="uil:shopping-cart"></a>
                </div>
              </div>
              <div class="product-item swiper-slide">
                <span class="badge bg-success position-absolute m-3">-15%</span>
                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                    <use xlink:href="#heart"></use>
                  </svg></a>
                <figure>
                  <a href="single-product.php" title="Product Title">
                    <img src="images/thumb-tomatoes.png" class="tab-image">
                  </a>
                </figure>
                <h3>Cà chua</h3>
                <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                    <use xlink:href="#star-solid"></use>
                  </svg> 4.5</span>
                <span class="price">18.000</span>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="input-group product-qty">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                        <svg width="16" height="16">
                          <use xlink:href="#minus"></use>
                        </svg>
                      </button>
                    </span>
                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                        <svg width="16" height="16">
                          <use xlink:href="#plus"></use>
                        </svg>
                      </button>
                    </span>
                  </div>
                  <a href="#" class="nav-link">Thêm vào giỏ <iconify-icon icon="uil:shopping-cart"></a>
                </div>
              </div>

              <div class="product-item swiper-slide">
                <span class="badge bg-success position-absolute m-3">-15%</span>
                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                    <use xlink:href="#heart"></use>
                  </svg></a>
                <figure>
                  <a href="single-product.php" title="Product Title">
                    <img src="images/thumb-tomatoes.png" class="tab-image">
                  </a>
                </figure>
                <h3>Cà chua</h3>
                <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                    <use xlink:href="#star-solid"></use>
                  </svg> 4.5</span>
                <span class="price">18.000</span>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="input-group product-qty">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                        <svg width="16" height="16">
                          <use xlink:href="#minus"></use>
                        </svg>
                      </button>
                    </span>
                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                        <svg width="16" height="16">
                          <use xlink:href="#plus"></use>
                        </svg>
                      </button>
                    </span>
                  </div>
                  <a href="#" class="nav-link">Thêm vào giỏ <iconify-icon icon="uil:shopping-cart"></a>
                </div>
              </div>

              <div class="product-item swiper-slide">
                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                    <use xlink:href="#heart"></use>
                  </svg></a>
                <figure>
                  <a href="single-product.php" title="Product Title">
                    <img src="images/thumb-bananas.png" class="tab-image">
                  </a>
                </figure>
                <h3>Sunstar Fresh Melon Juice</h3>
                <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                    <use xlink:href="#star-solid"></use>
                  </svg> 4.5</span>
                <span class="price">$18.00</span>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="input-group product-qty">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                        <svg width="16" height="16">
                          <use xlink:href="#minus"></use>
                        </svg>
                      </button>
                    </span>
                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                        <svg width="16" height="16">
                          <use xlink:href="#plus"></use>
                        </svg>
                      </button>
                    </span>
                  </div>
                  <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                </div>
              </div>

              <div class="product-item swiper-slide">
                <a href="#" class="btn-wishlist"><svg width="24" height="24">
                    <use xlink:href="#heart"></use>
                  </svg></a>
                <figure>
                  <a href="single-product.php" title="Product Title">
                    <img src="images/thumb-bananas.png" class="tab-image">
                  </a>
                </figure>
                <h3>Sunstar Fresh Melon Juice</h3>
                <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                    <use xlink:href="#star-solid"></use>
                  </svg> 4.5</span>
                <span class="price">$18.00</span>
                <div class="d-flex align-items-center justify-content-between">
                  <div class="input-group product-qty">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                        <svg width="16" height="16">
                          <use xlink:href="#minus"></use>
                        </svg>
                      </button>
                    </span>
                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                    <span class="input-group-btn">
                      <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                        <svg width="16" height="16">
                          <use xlink:href="#plus"></use>
                        </svg>
                      </button>
                    </span>
                  </div>
                  <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                </div>
              </div>

            </div>
          </div>
          <!-- / products-carousel -->

        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container-fluid">

      <div class="bg-secondary py-5 my-5 rounded-5"
        style="background: url('images/bg-leaves-img-pattern.png') no-repeat;">
        <div class="container my-5">
          <div class="row">
            <div class="col-md-6 p-5">
              <div class="section-header">
                <h2 class="section-title display-4">Get <span class="text-primary">25% Discount</span> on your first
                  purchase</h2>
              </div>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dictumst amet, metus, sit massa posuere
                maecenas. At tellus ut nunc amet vel egestas.</p>
            </div>
            <div class="col-md-6 p-5">
              <form>
                <div class="mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control form-control-lg" name="name" id="name" placeholder="Name">
                </div>
                <div class="mb-3">
                  <label for="" class="form-label">Email</label>
                  <input type="email" class="form-control form-control-lg" name="email" id="email"
                    placeholder="abc@mail.com">
                </div>
                <div class="form-check form-check-inline mb-3">
                  <label class="form-check-label" for="subscribe">
                    <input class="form-check-input" type="checkbox" id="subscribe" value="subscribe">
                    Subscribe to the newsletter</label>
                </div>
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-dark btn-lg">Submit</button>
                </div>
              </form>

            </div>

          </div>

        </div>
      </div>

    </div>
  </section>


  <section id="latest-blog" class="py-5">
    <div class="container-fluid">
      <div class="row">
        <div class="section-header d-flex align-items-center justify-content-between my-5">
          <h2 class="section-title">Our Recent Blog</h2>
          <div class="btn-wrap align-right">
            <a href="#" class="d-flex align-items-center nav-link">Read All Articles <svg width="24" height="24">
                <use xlink:href="#arrow-right"></use>
              </svg></a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <article class="post-item card border-0 shadow-sm p-3">
            <div class="image-holder zoom-effect">
              <a href="#">
                <img src="images/post-thumb-1.jpg" alt="post" class="card-img-top">
              </a>
            </div>
            <div class="card-body">
              <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                <div class="meta-date"><svg width="16" height="16">
                    <use xlink:href="#calendar"></use>
                  </svg>22 Aug 2021</div>
                <div class="meta-categories"><svg width="16" height="16">
                    <use xlink:href="#category"></use>
                  </svg>tips & tricks</div>
              </div>
              <div class="post-header">
                <h3 class="post-title">
                  <a href="#" class="text-decoration-none">Top 10 casual look ideas to dress up your kids</a>
                </h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipi elit. Aliquet eleifend viverra enim tincidunt donec
                  quam. A in arcu, hendrerit neque dolor morbi...</p>
              </div>
            </div>
          </article>
        </div>
        <div class="col-md-4">
          <article class="post-item card border-0 shadow-sm p-3">
            <div class="image-holder zoom-effect">
              <a href="#">
                <img src="images/post-thumb-2.jpg" alt="post" class="card-img-top">
              </a>
            </div>
            <div class="card-body">
              <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                <div class="meta-date"><svg width="16" height="16">
                    <use xlink:href="#calendar"></use>
                  </svg>25 Aug 2021</div>
                <div class="meta-categories"><svg width="16" height="16">
                    <use xlink:href="#category"></use>
                  </svg>trending</div>
              </div>
              <div class="post-header">
                <h3 class="post-title">
                  <a href="#" class="text-decoration-none">Latest trends of wearing street wears supremely</a>
                </h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipi elit. Aliquet eleifend viverra enim tincidunt donec
                  quam. A in arcu, hendrerit neque dolor morbi...</p>
              </div>
            </div>
          </article>
        </div>
        <div class="col-md-4">
          <article class="post-item card border-0 shadow-sm p-3">
            <div class="image-holder zoom-effect">
              <a href="#">
                <img src="images/post-thumb-3.jpg" alt="post" class="card-img-top">
              </a>
            </div>
            <div class="card-body">
              <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                <div class="meta-date"><svg width="16" height="16">
                    <use xlink:href="#calendar"></use>
                  </svg>28 Aug 2021</div>
                <div class="meta-categories"><svg width="16" height="16">
                    <use xlink:href="#category"></use>
                  </svg>inspiration</div>
              </div>
              <div class="post-header">
                <h3 class="post-title">
                  <a href="#" class="text-decoration-none">10 Different Types of comfortable clothes ideas for women</a>
                </h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipi elit. Aliquet eleifend viverra enim tincidunt donec
                  quam. A in arcu, hendrerit neque dolor morbi...</p>
              </div>
            </div>
          </article>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 my-5">
    <div class="container-fluid">

      <div class="bg-warning py-5 rounded-5" style="background-image: url('images/bg-pattern-2.png') no-repeat;">
        <div class="container">
          <div class="row">
            <div class="col-md-4">
              <img src="images/phone.png" alt="phone" class="image-float img-fluid">
            </div>
            <div class="col-md-8">
              <h2 class="my-5">Shop faster with foodmart App</h2>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sagittis sed ptibus liberolectus nonet
                psryroin. Amet sed lorem posuere sit iaculis amet, ac urna. Adipiscing fames semper erat ac in
                suspendisse iaculis. Amet blandit tortor praesent ante vitae. A, enim pretiummi senectus magna. Sagittis
                sed ptibus liberolectus non et psryroin.</p>
              <div class="d-flex gap-2 flex-wrap">
                <img src="images/app-store.jpg" alt="app-store">
                <img src="images/google-play.jpg" alt="google-play">
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <section class="py-5">
    <div class="container-fluid">
      <h2 class="my-5">People are also looking for</h2>
      <a href="#" class="btn btn-warning me-2 mb-2">Blue diamon almonds</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Angie’s Boomchickapop Corn</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Salty kettle Corn</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Chobani Greek Yogurt</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Sweet Vanilla Yogurt</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Foster Farms Takeout Crispy wings</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Warrior Blend Organic</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Chao Cheese Creamy</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Chicken meatballs</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Blue diamon almonds</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Angie’s Boomchickapop Corn</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Salty kettle Corn</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Chobani Greek Yogurt</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Sweet Vanilla Yogurt</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Foster Farms Takeout Crispy wings</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Warrior Blend Organic</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Chao Cheese Creamy</a>
      <a href="#" class="btn btn-warning me-2 mb-2">Chicken meatballs</a>
    </div>
  </section>

  <section class="py-5">
    <div class="container-fluid">
      <div class="row row-cols-1 row-cols-sm-3 row-cols-lg-5">
        <div class="col">
          <div class="card mb-3 border-0">
            <div class="row">
              <div class="col-md-2 text-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                  <path fill="currentColor"
                    d="M21.5 15a3 3 0 0 0-1.9-2.78l1.87-7a1 1 0 0 0-.18-.87A1 1 0 0 0 20.5 4H6.8l-.33-1.26A1 1 0 0 0 5.5 2h-2v2h1.23l2.48 9.26a1 1 0 0 0 1 .74H18.5a1 1 0 0 1 0 2h-13a1 1 0 0 0 0 2h1.18a3 3 0 1 0 5.64 0h2.36a3 3 0 1 0 5.82 1a2.94 2.94 0 0 0-.4-1.47A3 3 0 0 0 21.5 15Zm-3.91-3H9L7.34 6H19.2ZM9.5 20a1 1 0 1 1 1-1a1 1 0 0 1-1 1Zm8 0a1 1 0 1 1 1-1a1 1 0 0 1-1 1Z" />
                </svg>
              </div>
              <div class="col-md-10">
                <div class="card-body p-0">
                  <h5>Free delivery</h5>
                  <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card mb-3 border-0">
            <div class="row">
              <div class="col-md-2 text-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                  <path fill="currentColor"
                    d="M19.63 3.65a1 1 0 0 0-.84-.2a8 8 0 0 1-6.22-1.27a1 1 0 0 0-1.14 0a8 8 0 0 1-6.22 1.27a1 1 0 0 0-.84.2a1 1 0 0 0-.37.78v7.45a9 9 0 0 0 3.77 7.33l3.65 2.6a1 1 0 0 0 1.16 0l3.65-2.6A9 9 0 0 0 20 11.88V4.43a1 1 0 0 0-.37-.78ZM18 11.88a7 7 0 0 1-2.93 5.7L12 19.77l-3.07-2.19A7 7 0 0 1 6 11.88v-6.3a10 10 0 0 0 6-1.39a10 10 0 0 0 6 1.39Zm-4.46-2.29l-2.69 2.7l-.89-.9a1 1 0 0 0-1.42 1.42l1.6 1.6a1 1 0 0 0 1.42 0L15 11a1 1 0 0 0-1.42-1.42Z" />
                </svg>
              </div>
              <div class="col-md-10">
                <div class="card-body p-0">
                  <h5>100% secure payment</h5>
                  <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card mb-3 border-0">
            <div class="row">
              <div class="col-md-2 text-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                  <path fill="currentColor"
                    d="M22 5H2a1 1 0 0 0-1 1v4a3 3 0 0 0 2 2.82V22a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-9.18A3 3 0 0 0 23 10V6a1 1 0 0 0-1-1Zm-7 2h2v3a1 1 0 0 1-2 0Zm-4 0h2v3a1 1 0 0 1-2 0ZM7 7h2v3a1 1 0 0 1-2 0Zm-3 4a1 1 0 0 1-1-1V7h2v3a1 1 0 0 1-1 1Zm10 10h-4v-2a2 2 0 0 1 4 0Zm5 0h-3v-2a4 4 0 0 0-8 0v2H5v-8.18a3.17 3.17 0 0 0 1-.6a3 3 0 0 0 4 0a3 3 0 0 0 4 0a3 3 0 0 0 4 0a3.17 3.17 0 0 0 1 .6Zm2-11a1 1 0 0 1-2 0V7h2ZM4.3 3H20a1 1 0 0 0 0-2H4.3a1 1 0 0 0 0 2Z" />
                </svg>
              </div>
              <div class="col-md-10">
                <div class="card-body p-0">
                  <h5>Quality guarantee</h5>
                  <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card mb-3 border-0">
            <div class="row">
              <div class="col-md-2 text-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                  <path fill="currentColor"
                    d="M12 8.35a3.07 3.07 0 0 0-3.54.53a3 3 0 0 0 0 4.24L11.29 16a1 1 0 0 0 1.42 0l2.83-2.83a3 3 0 0 0 0-4.24A3.07 3.07 0 0 0 12 8.35Zm2.12 3.36L12 13.83l-2.12-2.12a1 1 0 0 1 0-1.42a1 1 0 0 1 1.41 0a1 1 0 0 0 1.42 0a1 1 0 0 1 1.41 0a1 1 0 0 1 0 1.42ZM12 2A10 10 0 0 0 2 12a9.89 9.89 0 0 0 2.26 6.33l-2 2a1 1 0 0 0-.21 1.09A1 1 0 0 0 3 22h9a10 10 0 0 0 0-20Zm0 18H5.41l.93-.93a1 1 0 0 0 0-1.41A8 8 0 1 1 12 20Z" />
                </svg>
              </div>
              <div class="col-md-10">
                <div class="card-body p-0">
                  <h5>guaranteed savings</h5>
                  <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card mb-3 border-0">
            <div class="row">
              <div class="col-md-2 text-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                  <path fill="currentColor"
                    d="M18 7h-.35A3.45 3.45 0 0 0 18 5.5a3.49 3.49 0 0 0-6-2.44A3.49 3.49 0 0 0 6 5.5A3.45 3.45 0 0 0 6.35 7H6a3 3 0 0 0-3 3v2a1 1 0 0 0 1 1h1v6a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3v-6h1a1 1 0 0 0 1-1v-2a3 3 0 0 0-3-3Zm-7 13H8a1 1 0 0 1-1-1v-6h4Zm0-9H5v-1a1 1 0 0 1 1-1h5Zm0-4H9.5A1.5 1.5 0 1 1 11 5.5Zm2-1.5A1.5 1.5 0 1 1 14.5 7H13ZM17 19a1 1 0 0 1-1 1h-3v-7h4Zm2-8h-6V9h5a1 1 0 0 1 1 1Z" />
                </svg>
              </div>
              <div class="col-md-10">
                <div class="card-body p-0">
                  <h5>Daily offers</h5>
                  <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipi elit.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <?php
  include "footer.php";
  ?>

</body>

</html>