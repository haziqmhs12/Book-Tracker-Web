/*  The following script references code from the tutorial:
    "Build A Simple Lyrics Fetcher App w Javascript"
    https://www.youtube.com/watch?v=s6iuMAmWsOg&t=4s 
    https://medium.com/@mbu58569/book-api-data-retrieval-project-8537350a4fe8
    */

// DOM elements

const titleInput = document.querySelector('#title');
const output = document.querySelector('.search-output pre');
const btn = document.querySelector('.fetchBtn');
const loading = document.querySelector('.loading');
const topResults = document.querySelector('#top_results');

// Add click event to button
btn.addEventListener('click', (event) => {
    
// Prevent the default form submission
    event.preventDefault();

    // Root link
    let url = "https://openlibrary.org/search.json?";

    //  Check for non-empty fields
    if ( titleInput.value !== "") {
        output.innerHTML = "";

        // Show loading div
        loading.style.opacity = "1";
        topResults.innerHTML=""
    
        // Input parameters
        if (titleInput.value !== "") {
            url += "q=" + titleInput.value ;
        } 

        // Replace space characters with '+'
        url = url.replace(" ", "+");

        fetch(url)  // Fetch results
       
       // Convert JSON-formatted data into a regular JS object
        .then(response => response.json())
       
        .then(data => {
            // If search results are found:
            if (data.docs !== undefined) {
                for (var i = 0; i < 10; i++) {
                    const li = document.createElement("div");
                    const li1 = document.createElement("div");
                    const div = document.createElement("div");
                    const divc = document.createElement("div");
                    const div1 = document.createElement("div");
                    const h5 = document.createElement("h5");
                    // const p1 = document.createElement("p");
                    div.className = "col-md-4";
                    divc.className = "col-md-8";
                    div1.className = "card-body";
                    h5.className = "card-title";
                    li.className = "card m-2";
                    li1.className = "row g-0";
                    


 //Result:
 //<div class="example-class another-class"></div>

                    let title = data.docs[i].title;
                    let author = data.docs[i].author_name;

                    // Attempt to fetch cover image
                    try {
                        let value = data.docs[i].isbn[0];
                        
                        let imageUrl = "https://covers.openlibrary.org/b/isbn/" + value + "-M.jpg";
                        div.innerHTML = "<img src ='" + imageUrl
                            + "' class='img-fluid rounded-start' id='cover'  style='height: 180px '>";
                        
                            fetch(imageUrl)
                            .then(response => response.blob())
                            .then(blob => {
                                if (blob.size === 43) { // Check if image size is 43 bytes
                                    div.innerHTML = "<img src='icons/image_unavailable.png' class='img-fluid rounded-start' id='cover' style='height: 180px '>";
                                } else {
                                    const imgUrl = URL.createObjectURL(blob);
                                    div.innerHTML = "<img src='" + imgUrl + "' class='img-fluid rounded-start' id='cover' >";
                                }
                            })
                            .catch(() => {
                                div.innerHTML = "<img src='icons/image_unavailable.png' class='img-fluid rounded-start' id='cover' style='height: 180px '>";
                            });
                        
                        
                    } catch (error) {
                        // No image available
                        div.innerHTML = "<img src=icons/image_unavailable.png id='cover' style='height: 180px'>";
                    }
                    h5.innerHTML = title;


                    li.appendChild(div);
                    li.appendChild(li1);
                    li1.appendChild(div);
                    li1.appendChild(divc);
                    divc.appendChild(div1);
                    div1.appendChild(h5);
                    output.appendChild(li);
                }
            } else {
                output.innerHTML = `No titles found for the given search.`;
            }
            // Hide loading div
            loading.style.opacity = "0";
            topResults.innerHTML="- Top Results -";

            // Show output div (fade-in animation)
            document.querySelector('.search-output').style.opacity = "1";
        });
    }
});


