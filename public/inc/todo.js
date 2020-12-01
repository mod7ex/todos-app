class Todo {
  constructor(_td_title = "", _td_content = "", _last_updated = "Not Saved Yet") {
    this.editMode = false;
    this.id = null;
    this.td_title = _td_title;
    this.td_content = _td_content;
    this.last_updated = _last_updated;

    // Dom Element
    this.domElm = null;
    this.domElm_td_title = null;
    this.domElm_td_content = null;
    this.domElm_td_title_input = null;
    this.domElm_td_content_input = null;
    this.domElm_last_updated = null;

    // Buttons
    this.delBtn = null;
    this.editBtn = null;

    // Create The Dom Element
    this.createDomElm();
  }

  createDomElm() {
    this.domElm = document.createElement("div");
    this.domElm.classList.add("col-md-3", "col-sm-4", "col-xs-6", "todo", "rounded", "m-2", "p-1");
    this.domElm.innerHTML = `
              <span class="td-title p-1">
                  <b ${(() => {
                    return this.editMode ? "class='hidden'" : "";
                  })()}>${this.td_title}
                  </b>
                  <input type="text" ${(() => {
                    return !this.editMode ? "class='hidden'" : "";
                  })()} maxlength="100">
                  <button class="float-right ml-2"><i class="fas fa-trash-alt"></i></button>
                  <button class="float-right"><i class="fas fa-edit"></i></button>
              </span>
              <div class="td-body p-1">
                  <textarea ${(() => {
                    return !this.editMode ? "class='hidden'" : "";
                  })()} cols="30" rows="10"></textarea>
                  <p ${(() => {
                    return this.editMode ? "class='hidden'" : "";
                  })()}>${this.td_content}</p>
              </div>
              <span class="p-1"><small>${this.last_updated}</small></span>`;
    document.querySelector("#todos-container").appendChild(this.domElm);

    //
    this.domElm_td_title = this.domElm.querySelector("span b");
    this.domElm_td_content = this.domElm.querySelector("div p");
    this.domElm_td_title_input = this.domElm.querySelector("span input");
    this.domElm_td_content_input = this.domElm.querySelector("div textarea");
    this.domElm_last_updated = this.domElm.querySelector("span small");

    // Buttons
    // Delete Button
    this.delBtn = this.domElm.querySelector("span button:first-of-type");
    this.delBtn.addEventListener("click", () => {
      this.delDomElm();
    });
    // Edit Button
    this.editBtn = this.domElm.querySelector("span button:last-of-type");
    this.editBtn.addEventListener("click", () => {
      this.chMode();
    });
  }

  saveInDb() {
    let xhr = new XMLHttpRequest();
    xhr.open("post", "todo/save", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send(
      JSON.stringify({
        id: this.id,
        td_title: this.td_title,
        td_content: this.td_content,
      })
    );

    xhr.onreadystatechange = () => {
      if (xhr.readyState == 4 && xhr.status == 200) {
        let resp = JSON.parse(xhr.responseText);

        if (Object.keys(resp).length != 0) {
          this.id = resp.id;
          this.domElm_last_updated.innerHTML = resp.last_updated;
        } else {
          last_updated.textContent = "Something Went Wrong";
          last_updated.style.color = "red";
        }
      }
    };
  }

  chMode() {
    if (this.editMode) {
      if (this.domElm_td_title_input.value != "" && this.domElm_td_content_input.value != "") {
        this.td_title = this.domElm_td_title.innerHTML = this.domElm_td_title_input.value;
        this.td_content = this.domElm_td_content.innerHTML = this.domElm_td_content_input.value;
        this.saveInDb();
      } else {
        return;
      }
    } else {
      this.domElm_td_title_input.value = this.domElm_td_title.textContent.trim();
      this.domElm_td_content_input.value = this.domElm_td_content.textContent.trim();
    }
    this.domElm_td_title.classList.toggle("hidden");
    this.domElm_td_title_input.classList.toggle("hidden");
    this.domElm_td_content_input.classList.toggle("hidden");
    this.domElm_td_content.classList.toggle("hidden");
    this.editMode = !this.editMode;
  }

  delDomElm() {
    if (this.id) {
      fetch(`todo/delete/${this.id}`, {
        method: "POST",
      })
        .then((resp) => {
          return resp.text();
        })
        .then((data) => {
          return new Promise((resolve, reject) => {
            if (data === "deleted") {
              resolve(this.domElm);
            } else {
              reject();
            }
          });
        })
        .then((elm) => {
          elm.remove();
        })
        .catch((e) => {
          this.domElm_last_updated.textContent = "Something Went Wrong";
          this.domElm_last_updated.style.color = "red";
        });
    } else {
      this.domElm.remove();
    }
  }
}

/*
let todo = new Todo("Mourad", "Content goes Here", "Not saved Yet");
todo.createDomElm();

todo.editBtn.addEventListener("click", () => {
  if (todo.editMode) {
    todo.saveInDb();
  }
  todo.chMode();
  console.log(todo.id);
  // console.log([todo.td_title, todo.td_content]);
});
*/

// Create A new Todo. +
document.querySelector("header button:last-of-type").addEventListener("click", () => {
  let newTodo = new Todo();
  newTodo.editBtn.click();
});

// List All the todos
fetch("todo/list", {
  method: "post",
  headers: {
    // "Content-Type": "application/json",
  },
})
  .then((resp) => {
    if (resp.status == 200) {
      return resp.json();
    } else {
      throw new Error("hello");
    }
  })
  .then((jsonData) => {
    jsonData.forEach((todo) => {
      let newTodo = new Todo(todo.td_title, todo.td_content, todo.last_updated);
      newTodo.id = todo.id;
      // newTodo.delBtn.click(); // auto deletion
    });
  })
  .catch((e) => {});