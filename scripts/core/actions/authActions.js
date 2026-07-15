import { logoutUser, loginUser, registerUser } from "../../services/userService.js";
import { setToken, logout } from "../../auth/auth.js";
import { authorize } from "./actions.js";
import { ROOT_DIR } from "../../config/config.js";
import { getMeContext, setMe } from "../../auth/authContext.js";


const authActions = {
    "logout": async () => {
        if(!await authorize())
        {
            console.warn("User is not logged");
            return;
        }

        await logoutUser();
        logout();
        location.href = `${ROOT_DIR}/index.html`;
    },

    "login": async (e) => {
        e.preventDefault();
        const form = e.target;

        const email = form.querySelector('.js-form-field input[type="email"]')?.value;
        const password = form.querySelector('.js-form-field input[type="password"]')?.value;

        console.log(email, password);
        try
        {
            const token = (await loginUser({ email, password })).value;
            setToken(token);

            const user = await getMeContext();
            setMe(user);

            location.href = `${ROOT_DIR}/index.html`;
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
    },

    "signup": async (e) => {
        e.preventDefault();
        const form = e.target;

        const username = form.querySelector('.js-form-field input[type="text"]')?.value;
        const email = form.querySelector('.js-form-field input[type="email"]')?.value;
        const password = form.querySelector('.js-form-field input[type="password"]')?.value;

        console.log(email, password, username);
        try
        {
            await registerUser({ username, email, password});

            location.href = `${ROOT_DIR}/index.html`;
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

export default authActions;