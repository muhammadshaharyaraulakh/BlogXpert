const addPostForm = document.getElementById("addPostForm"),
      titleError = addPostForm.querySelector(".title_error"),
      categoryError = addPostForm.querySelector(".category_error"),
      paraError = addPostForm.querySelector(".paragraphs_error"),
      thumbnailError = addPostForm.querySelector(".thumbnail_error"),
      generalError = addPostForm.querySelector(".general_error");

addPostForm.querySelectorAll("input,textarea,select").forEach(i => i.addEventListener("input", () => {
    titleError.textContent = categoryError.textContent = paraError.textContent = thumbnailError.textContent = generalError.textContent = "";
}));

addPostForm.addEventListener("submit", async e => {
    e.preventDefault();
    titleError.textContent = categoryError.textContent = paraError.textContent = thumbnailError.textContent = generalError.textContent = "";
    const formData = new FormData(addPostForm);
    try {
        const data = await (await fetch("addhandler.php", {method:"POST", body:formData})).json();
        if(data.status==="success") {
            showToast(data.message,data.status);
            setTimeout(()=>window.location.href=data.redirect,2000);
        } else {
            if(data.field==="title") titleError.textContent=data.message;
            else if(data.field==="category") categoryError.textContent=data.message;
            else if(data.field==="para_1"||data.field==="paragraphs") paraError.textContent=data.message;
            else if(data.field==="thumbnail"||data.field==="avatar") thumbnailError.textContent=data.message;
            else generalError.textContent=data.message;
        }
    } catch(err){showToast("System Error: Could not process request.","error");console.error(err);}
});
