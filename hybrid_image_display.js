// Hybrid Image Display - Show first image, hide rest with counter badge
document.addEventListener('DOMContentLoaded', function() {
    const postCards = document.querySelectorAll('.post-card');
    
    postCards.forEach(card => {
        const imageContainer = card.querySelector('.post-image-container');
        
        if (!imageContainer) return;
        
        // Get all images in this container
        const allImages = imageContainer.querySelectorAll('.post-image');
        const imageCount = allImages.length;
        
        if (imageCount > 1) {
            // Multiple images - hide all except first
            for (let i = 1; i < allImages.length; i++) {
                allImages[i].style.display = 'none';
            }
            
            // Add counter badge
            const additionalCount = imageCount - 1;
            const badge = document.createElement('div');
            badge.className = 'image-counter-badge';
            badge.textContent = `+${additionalCount} more`;
            
            imageContainer.style.position = 'relative';
            imageContainer.appendChild(badge);
        }
    });
    
    // Also watch for dynamically added posts (when scrolling loads more)
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(mutation => {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === 1 && node.classList && node.classList.contains('post-card')) {
                        // New post card added
                        const imageContainer = node.querySelector('.post-image-container');
                        
                        if (imageContainer) {
                            const allImages = imageContainer.querySelectorAll('.post-image');
                            const imageCount = allImages.length;
                            
                            if (imageCount > 1) {
                                // Hide all except first
                                for (let i = 1; i < allImages.length; i++) {
                                    allImages[i].style.display = 'none';
                                }
                                
                                // Add counter badge
                                const additionalCount = imageCount - 1;
                                const badge = document.createElement('div');
                                badge.className = 'image-counter-badge';
                                badge.textContent = `+${additionalCount} more`;
                                
                                imageContainer.style.position = 'relative';
                                imageContainer.appendChild(badge);
                            }
                        }
                    }
                });
            }
        });
    });
    
    // Watch for new posts added to the DOM
    const postsContainer = document.querySelector('.posts');
    if (postsContainer) {
        observer.observe(postsContainer, {
            childList: true,
            subtree: true
        });
    }
});