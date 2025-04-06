document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const statusDiv = document.querySelector('.status');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Show loading status
            statusDiv.classList.remove('hidden');
            statusDiv.innerHTML = '<p class="text-blue-600">Sending your message...</p>';
            
            // Get form data
            const formData = new FormData(contactForm);
            
            // Send form data via AJAX
            fetch('process_form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Display response message
                if (data.status === 'success') {
                    statusDiv.innerHTML = `<p class="text-green-600">${data.message}</p>`;
                    contactForm.reset(); // Reset form on success
                } else {
                    statusDiv.innerHTML = `<p class="text-red-600">${data.message}</p>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                statusDiv.innerHTML = '<p class="text-red-600">Something went wrong. Please try again later.</p>';
            });
        });
    }
}); 