import {test, expect} from '@playwright/test';

test("regarder le titre de la page d'accueil", async ({page}) => {

    await page.goto("http://localhost/cinetech/");

    await expect(page).toHaveTitle("Cinetech");

});

test("regarder la redirection page erreur 404", async ({page}) => {
    
    await page.goto("http://localhost/cinetech/azerty");

    await expect(page).toHaveTitle("Erreur 404");
    
});