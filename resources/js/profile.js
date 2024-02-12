import DOMPurify from "dompurify"

export default class Profile {
  constructor() {
    this.links = document.querySelectorAll(".profile-nav a")
    this.contentArea = document.querySelector(".profile-slot-content")
    this.events()
  }

  // events
  events() {
    addEventListener("popstate", () => {
      this.handleChange()
    })
    this.links.forEach(link => {
      link.addEventListener("click", e => this.handleLinkClick(e))
    })
  }

  handleChange() {
    this.contentArea.innerHTML = 'Loading...'; // Show loading indicator
    this.links.forEach(link => link.classList.remove("active"))
    this.links.forEach(async link => {
      if (link.getAttribute("href") == window.location.pathname) {
        try {
          const response = await axios.get(link.href + "/raw")
          this.updateContent(response, link)
        } catch (error) {
          console.error("Error fetching data: ", error)
        }
      }
    })
  }

  // methods
  async handleLinkClick(e) {
    this.links.forEach(link => link.classList.remove("active"))
    e.target.classList.add("active")
    e.preventDefault()
    try {
      const response = await axios.get(e.target.href + "/raw")
      this.updateContent(response, e.target)
      history.pushState({}, "", e.target.href)
    } catch (error) {
      console.error("Error fetching data: ", error)
    }
  }

  updateContent(response, link) {
    this.contentArea.innerHTML = DOMPurify.sanitize(response.data.theHTML)
    document.title = response.data.docTitle + " | OurApp"
    link.classList.add("active")
  }
}