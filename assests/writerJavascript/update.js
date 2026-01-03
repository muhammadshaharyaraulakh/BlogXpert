const toggleBtn = document.getElementById('sidebar-toggle');
const mainLayout = document.getElementById('main-layout');

toggleBtn.addEventListener('click', () => {
    mainLayout.classList.toggle('sidebar-active');
});

function showSection(sectionName) {
    const sections = ['manage', 'add', 'edit', 'contact', 'blog'];

    sections.forEach(sec => {
        const sectionEl = document.getElementById('view-' + sec);
        const navEl = document.getElementById('nav-' + sec);

        if (sec === sectionName) {
            sectionEl.classList.remove('hidden');
            if (navEl) navEl.classList.add('active');
        } else {
            sectionEl.classList.add('hidden');
            if (navEl) navEl.classList.remove('active');
        }
    });
}

async function prepareEdit(postId) {
    try {
        const response = await fetch(`get_post_details.php?id=${postId}`);
        const result = await response.json();

        if (result.status === 'success') {
            const post = result.data;
            const form = document.getElementById('Update');
            if (!form) return;

            const setVal = (selector, value) => {
                const field = form.querySelector(selector);
                if (field) field.value = value || "";
            };

            setVal('input[name="postId"]', post.id);
            setVal('input[name="title"]', post.title);
            setVal('select[name="category_id"]', post.category_id);

            for (let i = 1; i <= 6; i++) {
                setVal(`textarea[name="para_${i}"]`, post[`para_${i}`]);
            }

            showSection('edit');

        } else {
            alert("Error: " + result.message);
        }
    } catch (error) {
        console.error(error);
    }
}

document.addEventListener("input", () => {
    document.querySelectorAll(".alert__message.error").forEach(div => div.textContent = "");
});

const updateForm = document.getElementById("Update");

updateForm.addEventListener("submit", async function(e) {
    e.preventDefault();
    document.querySelectorAll(".alert__message.error").forEach(div => div.textContent = "");

    const formData = new FormData(this);

    try {
        const response = await fetch("update_post_handler.php", { method: "POST", body: formData });
        const data = await response.json();

        if (data.status === "success") {
            showToast("Update Successful",data.status);
            setTimeout(() => { window.location.href = "dashboard.php?section=manage"; }, 2000);
        } else {
            const errorDiv = this.querySelector(`.${data.field}_error`);
            if (errorDiv) {
                errorDiv.textContent = data.message;
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                alert(data.message);
            }
        }
    } catch (error) {
        console.error(error);
    }
});
