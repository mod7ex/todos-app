let image_profile_input = document.querySelector('#profile > input');
/*// Start Drag And Drop Files.
image_profile_input.previousElementSibling.addEventListener("dragenter", (e)=>{
    e.stopPropagation();
    e.preventDefault();
}, false);

image_profile_input.previousElementSibling.addEventListener("dragover", function(e){
    e.stopPropagation();
    e.preventDefault();
    this.style.color = 'green';
}, false);

image_profile_input.previousElementSibling.addEventListener("drop", (e)=>{
    e.stopPropagation();
    e.preventDefault();
  
    const dt = e.dataTransfer;
    const files = dt.files;
    image_profile_input.files = files;
    console.log(image_profile_input.files);
    // console.log(files[0]);   //*************
}, false);
// Start Drag And Drop Files.*/

image_profile_input.addEventListener('change', function(e){
    let img = document.createElement('img');
    img.src = window.URL.createObjectURL(this.files[0]);
    image_profile_input.previousElementSibling.innerHTML = '';
    image_profile_input.previousElementSibling.appendChild(img);
})

let showFeedBack = (feedBack, x) => {
    let feedBackArea = document.createElement("div");
    feedBackArea.classList.add("alert", "pt-1", `alert-${x}`);
    feedBackArea.setAttribute("role", "alert");
    feedBackArea.innerHTML = `<ul class="py-0 m-0"></ul>`;
    for (const key in feedBack) {
        if(Boolean(feedBack[key])){
            let li = document.createElement("li");
            li.innerHTML = feedBack[key];
            feedBackArea.querySelector("ul").appendChild(li);
        }
    }
    // document.querySelector('form').previousElementSibling.insertBefore(feedBackArea, document.querySelector("#editBtn"));
    document.querySelector('form').parentElement.insertBefore(feedBackArea, document.querySelector("#editBtn").parentElement);
    setTimeout(() => {
      feedBackArea.remove();
    }, 6000);
};


document.querySelector('form fieldset div input[type=submit]').addEventListener('click', (e)=>{
    e.preventDefault();
    data = {
        full_name:document.getElementById('full_name').value,
        oldpassword:document.getElementById('oldpassword').value,
        newpassword:document.getElementById('newpassword').value,
        repnewpassword:document.getElementById('repnewpassword').value,
    }
    if(data.full_name != ''){
        fetch("/user/edit/txtData", {
            method:'post',
            headers: {
                "Content-Type": "application/json",
                },
            body:JSON.stringify(data),
        }).then((resp)=>{
            if(resp){
                return resp.json();
            }else{
                throw new Error('');
            }
        }).then((fdbk)=>{
            if(fdbk.ok){
                document.querySelector('#editBtn').click();
                if(fdbk.full_name || fdbk.password){
                    showFeedBack({full_name:fdbk.full_name, password:fdbk.password}, 'primary');
                }
            }else{
                showFeedBack({error:fdbk.error}, 'danger');
            }
        }).catch((e)=>{});
    }

    // image Upload!
    if(image_profile_input.files.length != 0){
        let profileImg = image_profile_input.files[0];
        if(profileImg.type.startsWith('image/')){
            let formData = new FormData();
            formData.append('profileImg', profileImg);
            fetch('/user/edit/profileImg', {
                method:'post',
                body:formData
            }).then((resp)=>{
                if(resp.ok){
                    return resp.json();
                }else{
                    throw new Error('');
                }
            }).then((jsonData)=>{
                if(jsonData.profileUrlchanged){
                    showFeedBack({image:'Profile Image Changed With Success'}, 'primary');
                }else{
                    showFeedBack({image:jsonData.error}, 'danger');
                }
            }).catch((e)=>{})
        }
    }
});



document.getElementById('delAcc').addEventListener('click', ()=>{
    if(window.confirm('Are You Sure You want to Delete Your Account ?')){
        fetch('/user/delete', {
            method:'get',
        }).then((resp)=>{
            if(resp.ok){
                return resp.json();
            }else{
                throw new Error('error');
            }
        }).then((data)=>{
            if(data.accDel){
                window.location.href = '/';
            }else{
                throw new Error('error');
            }
        }).catch((e)=>{
            showFeedBack({error:'Some Thing Went Wrong'}, 'danger');
        })
    }
})