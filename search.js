/*  The following script references code from the tutorial:
    "Build A Simple Lyrics Fetcher App w Javascript"
    https://www.youtube.com/watch?v=s6iuMAmWsOg&t=4s 
    https://medium.com/@mbu58569/book-api-data-retrieval-project-8537350a4fe8
    */

// DOM elements

const titleInput = document.querySelector("#title");
const output = document.querySelector(".search-output pre");
const btn = document.querySelector(".fetchBtn");
const btn1 = document.querySelector(".Nextbutton");
const btn2 = document.querySelector(".Prevbutton");
const loading = document.querySelector(".loading");
const topResults = document.querySelector("#top_results");

var prevSearch = "";
var size = 12;
var page = 1;
// Add click event to button
btn.addEventListener("click", (event) => {
  // Prevent the default form submission
  event.preventDefault();
  prevSearch = titleInput.value;
  size = 12;
  page = 1;
  fetchBook();
});

function next() {
  if (page > 0) {
    page++;
    fetchBook();
  }
}

function prev() {
  if (page > 1) {
    page--;
    fetchBook();
  }
}

function fetchBook() {
  // Root link
  let url = "https://openlibrary.org/search.json?";

  //  Check for non-empty fields
  if (titleInput.value !== "") {
    output.innerHTML = "";

    // Show loading div

    loading.style.opacity = "1";
    topResults.innerHTML = "";

    // Input parameters
    if (titleInput.value !== "") {
      url += "title=" + prevSearch + "&lang=eng&limit=12&page=" + page;
    }

    // Replace space characters with '+'
    url = url.replace(" ", "+");

    fetch(url) // Fetch results
      // Convert JSON-formatted data into a regular JS object
      .then((response) => response.json())

      .then((data) => {
        // If search results are found:
        if (data.docs !== undefined && data.numFound != 0) {
          btn1.style.display = "block";
          if (data.numFound - data.start < size) {
            size = data.numFound - data.start - 1;
            btn1.style.display = "disabled";
          }

          for (var i = 0; i < size; i++) {
            // const li = document.createElement("div");
            // const li1 = document.createElement("div");
            // const div = document.createElement("div");
            // const divc = document.createElement("div");
            // const div1 = document.createElement("div");
            // const h5 = document.createElement("h5");

            const div = document.createElement("div");
            const div1 = document.createElement("div");
            const div2 = document.createElement("div");
            const div3 = document.createElement("div");
            const h5 = document.createElement("h5");
            const img = document.createElement("img");
            const p = document.createElement("p");
            const add = document.createElement("button");
            
            // const p1 = document.createElement("p");
            // div.className = "col-md-4";
            // divc.className = "col-md-8";
            // div1.className = "card-body";
            // h5.className = "card-title";
            // li.className = "card m-2";
            // li1.className = "row g-0";
            div.className = "col";
            div1.className = "card h-100";
            div2.className = "card-body d-flex flex-column";
            div3.className = "card-footer";
            h5.className = "card-title";
            p.className = "card-text";
            add.className = "mt-auto btn btn-primary insert-button";
            img.className = "card-img-top img-fluid img-thumbnail";
            div1.style.maxWidth = "20rem";
            

            //Result:
            //<div class="example-class another-class"></div>

            let title = data.docs[i].title;
            // Rest of the code related to title

            // Handle the case where title is not found

            // let title = data.docs[i].title;
            let author = data.docs[i].author_name;

            let value = data.docs[i].isbn ? data.docs[i].isbn[0] : '';

            // Attempt to fetch cover image
            try {
              // let value = data.docs[i].isbn[0];
              

              let imageUrl =
                "https://covers.openlibrary.org/b/isbn/" + value + "-M.jpg";
              // div1.innerHTML = "<img src ='" + imageUrl
              //     + "' class='card-img-top img-fluid img-thumbnail' id='cover'>";
              img.src = imageUrl;
              
              img.setAttribute('data-original-src', "icons/avatar_book-sm.png");
              img.setAttribute('data-isbn', value); // Store ISBN
              add.setAttribute('data-bs-target', "#staticBackdrop1");
              add.setAttribute('data-bs-toggle', "modal");
              

              fetch(imageUrl)
                .then((response) => response.blob())
                .then((blob) => {
                  if (blob.size === 43) {
                    // Check if image size is 43 bytes
                    // div1.innerHTML = "<img src='icons/avatar_book-sm.png'  class='card-img-top img-fluid img-thumbnail' id='cover'>";
                    img.src = "icons/avatar_book-sm.png";
                  } else {
                    const imgUrl = URL.createObjectURL(blob);
                    // div1.innerHTML = "<img src='" + imgUrl + "' class='card-img-top img-fluid img-thumbnail' id='cover' >";
                    img.src = imgUrl;
                    img.setAttribute('data-original-src', imageUrl); // Store original URL
                  }
                })
                .catch(() => {
                  // div1.innerHTML = "<img src='icons/avatar_book-sm.png' class='card-img-top img-fluid img-thumbnail' id='cover'>";
                  img.src = "icons/avatar_book-sm.png";
                });
            } catch (error) {
              // No image available
              // div1.innerHTML = "<img src='icons/avatar_book-sm.png' class='card-img-top img-fluid img-thumbnail' id='cover'>";
              img.src = "icons/avatar_book-sm.png";
            }
            if (author !== undefined) {
              p.innerText = author;
            }
           

            // h5.innerHTML = title;
            h5.innerText = title;
            add.innerText = "Read";
            
            

            // Check localStorage to see if this item was added before
            const bookKey = `${title}-${value}`;
                    if (localStorage.getItem(bookKey)) {
                        add.disabled = true;
                        add.innerText = "Added";
                    }

            div.appendChild(div1);
            div1.appendChild(img);
            div1.appendChild(div2);
            div2.appendChild(h5);
            div2.appendChild(p);
            div2.appendChild(add);
            // div3.appendChild(add);
            output.appendChild(div);
            topResults.style.opacity = "1";
            loading.style.opacity = "0";
            btn1.style.display = "block";
            btn2.style.display = "block";
            btn1.style.opacity = "1";
            btn2.style.opacity = "1";
          }
        } else {
          output.innerHTML = `No titles found for the given search.`;
          page = 0;
          btn1.style.display = "disabled";
          btn2.style.display = "disabled";
        }
        // Hide loading div

        topResults.innerHTML = "- Search Results -" + prevSearch;

        // Show output div (fade-in animation)
        document.querySelector(".search-output").style.opacity = "1";
      });
  }
}

document.addEventListener('DOMContentLoaded', function() {
  let title, authors, imageSrc, isbn, summary, rating; // Declare variables in a wider scope
  let addButton;
  
  // Delegate the click event handling to the parent element
  document.querySelector('.search-output').addEventListener('click', function(event) {
      // Check if the clicked element matches the .insert-button selector
    if (event.target && event.target.matches('.insert-button')) {
      
      // Disable the button to prevent multiple submissions
      addButton = event.target;  // Get the clicked button
      ;
      // Store the state in localStorage
      // const titles = addButton.closest('.card').querySelector('.card-title').innerText;
      // localStorage.setItem(titles, true);
          
      
      // Retrieve data from the clicked card
      const card = event.target.closest('.card');
      title = card.querySelector('.card-title').innerText;
      authors = card.querySelector('.card-text').innerText;
      imageSrc = card.querySelector('.card-img-top').getAttribute('data-original-src');
      isbn = card.querySelector('.card-img-top').getAttribute('data-isbn');
    }

    if (event.target && event.target.matches('.descButton')) {
      
          const form = document.getElementById('bookForm'); // Assuming your form has an ID 'bookForm'
        if (form) {
          // Populate the hidden inputs in the modal form
          document.getElementById("bookTitle").value = title;
          document.getElementById("bookAuthors").value = authors;
          document.getElementById("bookImageSrc").value = imageSrc;
          document.getElementById("bookIsbn").value = isbn;

          /// Access input values directly
          summary = document.getElementById("summary").value.trim(); // Trimmed summary value
          rating = document.getElementById("rating").value.trim(); // Trimmed rating value

          // Check if summary or rating is empty
          if (summary === '' && rating === '') {
            alert("Please enter summary or rating.");
            return; // Exit function if both are empty
          }
          if (rating > 5) {
            rating = 5;
          }
        }
        
        const key = `${title}-${isbn}`;
            

          // Create an object with the data
          const data = {
              title: title,
              authors: authors,
            imageSrc: imageSrc,
            summary: summary,
              rating : rating,
              userID: userID
          };

          // Send the data to the PHP script using fetch
          fetch("insert.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
          })
            .then((response) => {
              if (!response.ok) {
                throw new Error("Failed to add data to database.");
              }
              return response.json(); // Parse the JSON response
            })
            .then((jsonResponse) => {
              if (jsonResponse.error) {
                throw new Error(jsonResponse.error);
              }

              // If no errors, proceed with success handling
              alert("Data added to database successfully!");
              addButton.disabled = true;
              addButton.innerText = "Added";
            })
            .catch((error) => {
                console.error("Error:", error);
              // Enable the button and reset text in case of an error
              addButton.disabled = false;
              addButton.innerText = "Read";
            });

      }
  });
});

// list

// add process to enter data into database
