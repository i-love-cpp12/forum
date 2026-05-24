export default function renderProfileForm(user, profileForm)
{
    profileForm.querySelector(".js-form-field input[type='email']").value = user.email.toLowerCase();
    profileForm.querySelector(".js-form-field input[type='text']").value = user.username;
    profileForm.querySelector(".js-form-field input[type='password']").value = "";
}