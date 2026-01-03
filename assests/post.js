const toggleCommentsBtn = document.getElementById('toggle-comments-btn');
const commentsSection = document.getElementById('comments-section');

toggleCommentsBtn.addEventListener('click', () => {
    commentsSection.classList.toggle('show');

    if (commentsSection.classList.contains('show')) {
        toggleCommentsBtn.innerHTML = '<i class="uil uil-angle-up"></i> Hide Comments';
    } else {
        toggleCommentsBtn.innerHTML = '<i class="uil uil-comment-alt-lines"></i> Show Comments';
    }
});

const openPopupBtn = document.getElementById('open-comment-popup');
    const commentPopup = document.getElementById('comment-popup');
    const closePopupBtn = document.getElementById('close-popup-btn');

    if (openPopupBtn && commentPopup && closePopupBtn) {
        openPopupBtn.addEventListener('click', () => {
            commentPopup.style.display = 'grid'; // show popup
        });

        closePopupBtn.addEventListener('click', () => {
            commentPopup.style.display = 'none';
        });

        commentPopup.addEventListener('click', (e) => {
            if (e.target.id === 'comment-popup') {
                commentPopup.style.display = 'none';
            }
        });
    }

const likeForm = document.querySelector('.like-form');

if (likeForm) {
    likeForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(likeForm);
        const likeBtn = likeForm.querySelector('.like-btn');
        const likeBtnSpan = likeBtn.querySelector('span');

        try {
            const response = await fetch('likehandler.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                likeBtnSpan.textContent = data.likes + " Likes";
                likeBtn.disabled = true;
                likeBtn.classList.add('disabled-like');
                showToast(data.message, 'success'); 
            } else {
                showToast(data.message, 'error');
                if (data.message.includes("already liked")) {
                    likeBtn.disabled = true;
                    likeBtn.classList.add('disabled-like');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showToast("Connection failed.", "error"); 
        }
    });
}

const commentForm = document.querySelector('form[action="/pages/comment.php"]');

if (commentForm) {
    commentForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(commentForm);
        const submitBtn = commentForm.querySelector('.comment-btn');
        const textarea = commentForm.querySelector('textarea[name="body"]');

        submitBtn.disabled = true;

        try {
            const response = await fetch(commentForm.getAttribute('action'), {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                showToast(data.message, 'success');
                textarea.value = '';
            } else {
                showToast(data.message, 'error');
            }

            submitBtn.disabled = false;

        } catch (error) {
            console.error(error);
            showToast("Connection failed.", 'error');
            submitBtn.disabled = false;
        }
    });
}


const deleteCommentForms = document.querySelectorAll('.delete-comment-form');

deleteCommentForms.forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        const commentCard = form.closest('.comment');
        const deleteBtn = form.querySelector('button');

        deleteBtn.disabled = true;

        try {
            const response = await fetch(form.getAttribute('action'), {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                showToast(data.message, 'success');

                if (commentCard) {
                    commentCard.style.opacity = '0';
                    setTimeout(() => commentCard.remove(), 300);
                }
            } else {
                showToast(data.message, 'error');
                deleteBtn.disabled = false;
            }

        } catch (error) {
            console.error(error);
            showToast("Connection failed.", 'error');
            deleteBtn.disabled = false;
        }
    });
});

