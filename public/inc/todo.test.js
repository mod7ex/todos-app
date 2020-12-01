let nullTodo = { id: null, td_title: "", td_content: "", created_at: null, last_updated: "Not Saved Yet" };

let createTodo = (td = nullTodo, editMode = false) => {
  let todo = document.createElement("div");
  todo.classList.add("col-md-3", "col-sm-4", "col-xs-6", "todo", "rounded", "m-2", "p-1");
  todo.innerHTML = `
            <span class="td-title p-1">
                <b ${(() => {
                  return editMode ? "class='hidden'" : "";
                })()}>${td.td_title}
                </b>
                <input type="text" ${(() => {
                  return !editMode ? "class='hidden'" : "";
                })()} maxlength="100">
                <button class="float-right ml-2"><i class="fas fa-trash-alt"></i></button>
                <button class="float-right"><i class="fas fa-edit"></i></button>
            </span>
            <div class="td-body p-1">
                <textarea ${(() => {
                  return !editMode ? "class='hidden'" : "";
                })()} cols="30" rows="10"></textarea>
                <p ${(() => {
                  return editMode ? "class='hidden'" : "";
                })()}>${td.td_content}</p>
            </div>
            <span class="p-1"><small>${td.last_updated}</small></span>`;
  document.querySelector("#todos-container").appendChild(todo);
  return { td: todo, editMode: editMode, domElm: todo };
};

// let todo = createTodo((td = nullTodo), (editMode = true));

let gen = (td, editMode) => {
  let foo = () => {
    let todo = createTodo(td, editMode);

    td_title = todo.td.querySelector("span b");
    td_content = todo.td.querySelector("div p");
    td_title_input = todo.td.querySelector("span input");
    td_content_input = todo.td.querySelector("div textarea");
    last_updated = todo.td.querySelector("span small");

    // Trash Button
    todo.td.querySelector("span button:first-of-type").addEventListener("click", () => {
      if (todo.td.id) {
        fetch(`todo/delete/${todo.td.id}`, {
          method: "POST",
        })
          .then((resp) => {
            return resp.text();
          })
          .then((data) => {
            return new Promise((resolve, reject) => {
              if (data === "deleted") {
                resolve(todo.domElm);
                // todo.domElm.remove();
              } else {
                reject();
                // throw new Error(" ");
              }
            });
          })
          .then((elm) => {
            elm.remove();
          })
          .catch((e) => {
            last_updated.textContent = "Something Went Wrong";
            last_updated.style.color = "red";
          });
      } else {
        todo.td.remove();
      }
    });

    // Edit Button
    todo.td.querySelector("span button:last-of-type").addEventListener("click", () => {
      if (todo.editMode) {
        if (td_title_input.value != "" && td_content_input.value != "") {
          td_title.innerHTML = td_title_input.value;
          td_content.innerHTML = td_content_input.value;

          // ajax call *******************************************************************
          todoObjJson = JSON.stringify({
            id: todo.td.id,
            td_title: td_title_input.value,
            td_content: td_content_input.value,
          });

          let xhr = new XMLHttpRequest();
          xhr.open("post", "todo/save", true);
          xhr.setRequestHeader("Content-Type", "application/json");
          xhr.send(todoObjJson);

          // We use function keyword because arrow notation will not know what 'this(keyword)' does mean.
          xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
              respond = JSON.parse(this.responseText);

              if (Object.keys(respond).length != 0) {
                todo.td = respond;
                last_updated.innerHTML = respond.last_updated;

                td_title.classList.toggle("hidden");
                td_title_input.classList.toggle("hidden");
                td_content_input.classList.toggle("hidden");
                td_content.classList.toggle("hidden");
                todo.editMode = !todo.editMode;
              } else {
                last_updated.textContent = "Something Went Wrong";
                last_updated.style.color = "red";
              }
            }
          };
          // End ajax call **************************************************************
        }
      } else {
        td_title_input.value = td_title.textContent;
        td_content_input.value = td_content.textContent;

        td_title.classList.toggle("hidden");
        td_title_input.classList.toggle("hidden");
        td_content_input.classList.toggle("hidden");
        td_content.classList.toggle("hidden");
        todo.editMode = !todo.editMode;
      }
    });
  };
  return foo;
};

// Create A new Todo. +
document.querySelector("header button:last-of-type").addEventListener("click", gen((td = nullTodo), (editMode = true)));

// List All the todos
fetch("todo/list", {
  method: "post",
  headers: {
    "Content-Type": "application/json",
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
      gen((td = todo), (editMode = false))();
    });
    // if (Object.keys(jsonData).length === 0) {
    //   throw new Error("hello");
    // } else {
    //   todo.td = jsonData;
    //   last_updated.innerHTML = jsonData.last_updated;

    //   td_title.classList.toggle("hidden");
    //   td_title_input.classList.toggle("hidden");
    //   td_content_input.classList.toggle("hidden");
    //   td_content.classList.toggle("hidden");
    //   todo.editMode = !todo.editMode;
    // }
  })
  .catch((e) => {});
