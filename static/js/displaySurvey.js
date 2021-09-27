// Controls the visuals for the 'star rating' feature

function starClicked(star)
{
    var selectedStar = star;
    var stopSelection = false;
    var container = document.getElementById(star.id.slice(0, -1));
    container.innerHTML= star.id.slice(-1);

    var stars = document.getElementById(selectedStar.id).parentElement.children;
    for(var i = 0; i < stars.length; i++)
    {
        var starChild = stars[i];
        if(stopSelection)
        {
            starChild.classList.remove('selected');
        }
        else
        {
            starChild.classList.add('selected');
        }

        if(starChild == selectedStar)
        {
            stopSelection = true;

        }
    }
}