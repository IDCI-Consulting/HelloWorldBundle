const hljs = require('highlight.js');
import 'highlight.js/styles/panda-syntax-dark.css';
hljs.highlightAll();

hljs.addPlugin({
    "after:highlightElement": ({ el, text }) => {
      /**
       * el is the <code> element that was highlighted
       * el.parentElement is the <pre> element
       */
      const wrapper = el.parentElement;
      if (wrapper == null) {
        return;
      }

      /**
       * Make the parent relative so we can absolutely
       * position the copy button
       */
      wrapper.classList.add("relative");

      const copyButton = document.createElement("button");
      copyButton.classList.add(
        "absolute",
        "top-2",
        "right-2",
        "p-2",
        "text-gray-500",
        "hover:text-gray-700",
      );
      // Lucide copy icon
      copyButton.innerHTML = `<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-copy"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>`;

      copyButton.onclick = () => {
        window.navigator.clipboard.writeText(text);
      };

      // Append the copy button to the wrapper
      wrapper.appendChild(copyButton);
    },
});