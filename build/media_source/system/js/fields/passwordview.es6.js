/**
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
((document) => {
  'use strict';

  document.addEventListener('DOMContentLoaded', () => {
    [].slice.call(document.querySelectorAll('input[type="password"]')).forEach((input) => {
      const inputGroup = input.parentNode.querySelector('.input-group-prepend, .input-group-append');

      if (!inputGroup) {
        return;
      }

      inputGroup.addEventListener('click', (e) => {
        const { target } = e;
        const srText = target.previousSibling;

        if (target.classList.contains('icon-eye')) {
          // Update the icon class
          target.classList.remove('icon-eye');
          target.classList.add('icon-eye-close');

          // Update the input type
          input.type = 'text';

          // Update the text for screenreaders
          srText.innerText = Joomla.JText._('JSHOW');
        } else {
          // Update the icon class
          target.classList.add('icon-eye');
          target.classList.remove('icon-eye-close');

          // Update the input type
          input.type = 'password';

          // Update the text for screenreaders
          srText.innerText = Joomla.JText._('JHIDE');
        }
      });
    });
  });
})(document);
