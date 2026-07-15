document.addEventListener('DOMContentLoaded', () => {

    const section = document.querySelector('.cta');

    if (!section) {
        return;
    }

    const observer = new IntersectionObserver((entries) => {

        entries.forEach(entry => {

            if (!entry.isIntersecting) {
                return;
            }

            section.classList.add('is-visible');

            observer.disconnect();

        });

    }, {
        threshold: 0.35
    });

    observer.observe(section);

});

document.addEventListener(
    'DOMContentLoaded',
    () => {


        const form =
            document.querySelector('#lead-form');


        if(!form)
            return;



        form.addEventListener(
            'submit',
            async(e)=>{


                e.preventDefault();


                const button =
                    form.querySelector('button');


                button.classList.add('loading');

                button.disabled=true;



                const data =
                    new FormData(form);



                try {


                    const response =
                        await fetch(
                            '/lead',
                            {

                                method:'POST',

                                headers:{

                                    'X-CSRF-TOKEN':
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .content

                                },

                                body:data

                            }
                        );

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error(errorText);
                        throw new Error('Server error');
                    }

                    const result = await response.json();


                    if(result.success){


                        form.reset();


                        showLeadMessage(
                            result.message
                        );


                    }


                }
                catch(error){


                    console.error(error);


                }
                finally{


                    button.classList.remove('loading');

                    button.disabled=false;


                }


            }
        );


    }
);



function showLeadMessage(message){


    const box =
        document.createElement('div');


    box.className =
        'lead-success';


    box.innerHTML =
        message;



    document.body.append(box);



    setTimeout(()=>{


        box.classList.add('show');


    },50);



    setTimeout(()=>{


        box.classList.remove('show');


        setTimeout(()=>box.remove(),300);


    },4000);


}
