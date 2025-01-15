//sidebar
const allDropdown = document.querySelectorAll('#sidebar .side-dropdown');

allDropdown.forEach(item =>{
    const a = item.parentElement.querySelector('a:first-child');
    a.addEventListener('click', function (e){
        e.preventDefault();

        if(!this.classList.contains('active')){
            allDropdown.forEach(i =>{
                const aLink = i.parentElement.querySelector('a:first-child');

                aLink.classList.remove('active');
                i.classList.remove('show');
            })
        }

        this.classList.toggle('active');
        item.classList.toggle('show');
    })
})



//profile dropdown
const profile = document.querySelector('nav .profile');
const imgProfile = profile.querySelector('img');
const dropdownProfile = profile.querySelector('.profile-link');

imgProfile.addEventListener('click', function (){
    dropdownProfile.classList.toggle('show');
})


window.addEventListener('click', function (e){
    if(e.target !==imgProfile){
        if(e.target !==dropdownProfile){
            if(dropdownProfile.classList.contains('show')){
                dropdownProfile.classList.remove('show');
            }
        }
    }
})





//sidebar collapse
const toggleSidebar = document.querySelector('nav .toggle-sidebar');

const allSideDivider =document.querySelectorAll('#sidebar .divider');

  if(sidebar.classList.contains('hide')){
    allSideDivider.forEach(item =>{
      item.textContent = '';
    })

    allDropdown.forEach(item =>{
      const a = item.parentElement.querySelector('a:first-child');
      a.classList.remove('active');
      item.classList.remove('show');
  })
  
  }else{
    allSideDivider.forEach(item =>{
      item.textContent = item.dataset.text;
    })
  }

toggleSidebar.addEventListener('click', function (){
  sidebar.classList.toggle('hide');

  if(sidebar.classList.contains('hide')){
    allSideDivider.forEach(item =>{
      item.textContent = '';
    })

    allDropdown.forEach(item =>{
      const a = item.parentElement.querySelector('a:first-child');
      a.classList.remove('active');
      item.classList.remove('show');
  })
  
  }else{
    allSideDivider.forEach(item =>{
      item.textContent = item.dataset.text;
    })
  }
})

//sidebar leave
sidebar.addEventListener('mouseleave'), function (){
  if(this.classList.contains('hide')){
    allDropdown.forEach(item=>{
      const a =item.parentElement.querySelector('a:first-child');
      a.classList.remove('active');
      item.classList.remove('show');
    })
    allSideDivider.forEach(item =>{
      itemm.textContent = '';
    })
  }
}

sidebar.addEventListener('mouseenter'), function (){
  if(this.classList.contains('hide')){
    allDropdown.forEach(item=>{
      const a =item.parentElement.querySelector('a:first-child');
      a.classList.remove('active');
      item.classList.remove('show');
    })
    allSideDivider.forEach(item =>{
      itemm.textContent = '';
    })
  }
}






