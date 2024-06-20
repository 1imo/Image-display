<?php
$directory = './';
$images = glob($directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
$image_list = json_encode(array_map('basename', $images));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
    <style>
    .gallery {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 16px;
        padding: 16px;
    }
    .gallery .image-container {
        position: relative;
        width: 100%;
        padding-bottom: 100%;
        background-color: #f0f0f0;
        animation: pulse 1.5s infinite;
    }
    .gallery img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
        display: none;
    }
    @keyframes pulse {
        0% {
            background-color: #f0f0f0;
        }
        50% {
            background-color: #e0e0e0;
        }
        100% {
            background-color: #f0f0f0;
        }
    }
    @media (min-width: 550px) {
        .gallery { grid-template-columns: repeat(2, 1fr); }
    }
    @media (min-width: 800px) {
        .gallery { grid-template-columns: repeat(4, 1fr); }
    }
    @media (min-width: 1200px) {
        .gallery { grid-template-columns: repeat(6, 1fr); }
    }
    .modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-height: calc(100vh - 32px);
        max-width: calc(100vw - 32px);
        width: auto;
        height: auto;
        object-fit: contain;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
        padding: 16px;
        box-sizing: border-box;
    }

    .close {
        position: absolute;
        top: 16px;
        right: 16px;
        color: #f1f1f1;
        font-size: 32px;
        line-height: 32px;
        font-weight: bold;
        cursor: pointer;
        z-index: 1001;
    }

    .nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: #fff;
        font-size: 24px;
        padding: 8px;
        border: none;
        cursor: pointer;
        z-index: 1001;
    }

    .nav-btn.next {
        right: 16px;
    }

    .nav-btn.prev {
        left: 16px;
    }
    </style>
</head>
<body>
    <div id="imageGallery" class="gallery"></div>
    <div id="modal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImg">
        <button class="nav-btn prev" id="prevBtn">&lt;</button>
        <button class="nav-btn next" id="nextBtn">&gt;</button>
    </div>
    <script>
    const pathArray = window.location.pathname.split('/');
    const directoryName = pathArray[pathArray.length - 2];
    document.title = `Image Gallery - ${directoryName}`;
    document.addEventListener('DOMContentLoaded', function() {
        const images = <?php echo $image_list; ?>;
        const gallery = document.getElementById('imageGallery');
        const modal = document.getElementById('modal');
        const modalImg = document.getElementById('modalImg');
        const closeModal = document.querySelector('.close');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        let currentImageIndex = 0;

        images.forEach((src, index) => {
            const container = document.createElement('div');
            container.className = 'image-container';
            const img = document.createElement('img');
            img.src = src;
            img.onload = () => {
                container.style.animation = 'none';
                img.style.display = 'block';
            };
            container.appendChild(img);
            gallery.appendChild(container);

            img.addEventListener('click', () => {
                modal.style.display = 'block';
                modalImg.src = src;
                currentImageIndex = index;
            });
        });

        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        function showNextImage() {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            modalImg.src = images[currentImageIndex];
        }

        function showPrevImage() {
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            modalImg.src = images[currentImageIndex];
        }

        nextBtn.addEventListener('click', showNextImage);
        prevBtn.addEventListener('click', showPrevImage);
    });
    </script>
</body>
</html>
