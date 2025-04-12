import $ from "jquery"
/**
 * Class KbPostsCounter
 */
export default class KbPostsCounter {
  /**
   * Run when the document is ready.
   *
   * @return {void}
   */

  constructor() {
    this.cache()
  }

  cache() {
    this.listItems = $(".dynamic-list-counter")
  }

  docReady() {
    if (this.listItems.length === 0) return ""

    this.render()
  }

  render() {
    this.listItems.each((index, item) => {
      const listItem = $(item)
      const page = parseInt(listItem.attr("data-page"), 10) || 1
      const perPage = parseInt(listItem.attr("data-items-per-page"), 10) || 1
      const offset = (page - 1) * perPage

      listItem.find("a").each((index, element) => {
        $(element).attr("data-counter", offset + index + 1)
      })
    })
  }
}
