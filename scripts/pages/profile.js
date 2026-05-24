import bootstrap from "../core/bootstrap.js";
import { getMeContext } from "../auth/authContext.js";
import renderProfileForm from "../ui/profileUI.js";

function loadProfile(form)
{
    const profile = getMeContext();
    console.log(profile);
    renderProfileForm(profile, form);
}

async function init()
{
    await bootstrap();
    const profileForm = document.querySelector(".js-profile-form");
    loadProfile(profileForm);
}

init();