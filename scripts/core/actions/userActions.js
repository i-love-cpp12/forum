import { updateUser, getMe } from "../../services/userService.js";
import { getMeContext, setMe } from "../../auth/authContext.js";
import { updateHeader } from "../../ui/headerUI.js";
import renderProfileForm from "../../ui/profileUI.js";


const userActions = {
    "edit-profile": async (e) => {
        e.preventDefault();
            
        console.log("edited")
        const form = e.target;
        
        form.querySelectorAll(".js-form-field .error")
                .forEach(errorElem => errorElem.textContent = "");
        form.querySelectorAll(".js-form-field .text-input")
            .forEach(inputElem => inputElem.classList.remove("error"));

        const username = form.querySelector('.js-form-field input[type="text"]')?.value?.trim();
        const password = form.querySelector('.js-form-field input[type="password"]')?.value?.trim();
       
        try
        {
            let user = getMeContext();
            const newData = { username };

            if(password.length > 0)
                newData.password = password;

            console.log(newData);
            await updateUser(user.id, newData);

            user = await getMe();
            setMe(user);

            updateHeader({
                username: user?.username,
                email: user?.email
            });

            renderProfileForm(user, form);
        }
        catch
        {
            form.querySelectorAll(".js-form-field .error")
                .forEach(errorElem => {
                    errorElem.textContent = "Something went wrong";
                    errorElem.classList.add("active");
                });
            form.querySelectorAll(".js-form-field .text-input")
                .forEach(inputElem => inputElem.classList.add("error"));
        }
    }
};

export default userActions;