function processImage() {
    // alert("TES");
    const fileInput = document.getElementById('image-file');
    const file = fileInput.files[0];
    const reader = new FileReader();

    reader.onloadend = () => {
        const uploadedImage = document.getElementById('uploaded-image');
        const processedImage = document.getElementById('processed-image');
        const greenCoverPercentage = document.getElementById('green-cover-percentage');
        const idleLandPercentage = document.getElementById('idle-land-percentage');

        const img = new Image();
        img.onload = () => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);

            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let blackPixelCount = 0;

            // Calculate the mean green value
            let totalGreen = 0;
            for (let i = 1; i < imageData.data.length; i += 4) {
                totalGreen += imageData.data[i];
            }
            const meanGreen = totalGreen / (imageData.data.length / 4);

            for (let i = 0; i < imageData.data.length; i += 4) {
                let gray = (imageData.data[i + 1]) * 0.587; // Keep only green channel

                if (gray < meanGreen / 1.5) {
                    gray = 0;
                    blackPixelCount++;
                } else {
                    gray = 255;
                }

                imageData.data[i] = gray;   // Red channel
                imageData.data[i + 1] = gray; // Green channel
                imageData.data[i + 2] = gray; // Blue channel
                imageData.data[i + 3] = 255;  // Alpha channel
            }

            ctx.putImageData(imageData, 0, 0);

            // Display the processed image and green cover percentage
            processedImage.src = canvas.toDataURL();
            greenCoverPercentage.textContent = ((blackPixelCount / (canvas.width * canvas.height)) * 100).toFixed(2) + '%';
            idleLandPercentage.textContent = (100 - ((blackPixelCount / (canvas.width * canvas.height)) * 100)).toFixed(2) + '%';
        };
        img.src = reader.result;

        // Display the uploaded image
        uploadedImage.src = reader.result;
    };

    reader.readAsDataURL(file);
  }
