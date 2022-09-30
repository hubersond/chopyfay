import { useAppBridge } from "@shopify/app-bridge-react";
import { authenticatedFetch } from "@shopify/app-bridge-utils";
import { Button, Form, FormLayout, TextField, } from "@shopify/polaris";
import { useCallback, useState } from "react";
import { useMutation } from "react-query";
import { useAppQuery, useAuthenticatedFetch } from "../../hooks";

export default function ProductForm() {
    const [isLoading, setIsLoading] = useState(true);
    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');
    const [image, setImage] = useState('');

    // const app = useAppBridge();
    const authedFetch = useAuthenticatedFetch();
    const post = async () => {
        let headers = {
            'Accept': '*/*',
            'Accept-Language': 'en-US,en;q=0.5',
            'Accept-Encoding': 'gzip, deflate, br',
            'Referer': '',
            'authorization': 'Bearer ..',
            'x-requested-with': 'XMLHttpRequest',
            'Connection': 'keep-alive',
            'Cookie': 'chopy_fay_session=...',
            'Sec-Fetch-Dest': 'empty',
            'Sec-Fetch-Mode': 'cors',
            'Sec-Fetch-Site': 'same-origin',
            'Pragma': 'no-cache',
            'Cache-Control': 'no-cache',
            'TE': 'trailers',
        };

        let url = '/api/rest/products/create'
        const res = authedFetch(url, {
            // headers,
            method: 'POST',
            body: JSON.stringify({
                title,
                description,
                image,
            })
        });

        return res;
    };

    // const post = authenticatedFetch(app);
    const addProduct = () => {
        post();
        // console.log(JSON.stringify(data.form))
        // const post = authenticatedFetch(app);

        // return async () => {
        //     const res = post('/api/rest/products/create', {
        //         method: 'POST',
        //         body: JSON.stringify({
        //             title,
        //             description,
        //             image,
        //         })
        //     });

        //     return res;
        // }

        // checkHeadersForReauthorization(response.headers, app);

        // return response;

        // const { product } = post({
        //     url: '/api/rest/products/create',
        //     method: 'POST',
        //     body: JSON.stringify({
        //         title,
        //         description,
        //         image,
        //     }),
        //     reactQueryOptions: {
        //         onSuccess: () => {
        //             setIsLoading(false);
        //             // console.log('created product:?', product);
        //         },
        //     },
        // });
    };

    // const mutation = useMutation((data) => {
    //     return post('/api/rest/products/create', {
    //         method: 'POST',
    //         body: JSON.stringify(data)
    //     })
    // })

    return (
        <Form onSubmit={addProduct}>
        {/* <Form onSubmit={() => { mutation.mutate({ title, description, image, }) }}> */}
            <TextField
                label="Title" type="text"
                value={title}
                onChange={(value) => setTitle(value)}
            />

            <TextField
                label="Description" type="text"
                multiline={4}
                value={description}
                onChange={(value) => setDescription(value)}
            />

            <TextField
                label="Image Source" type="url"
                value={image}
                onChange={(value) => setImage(value)}
            />

            <Button submit>Submit</Button>
        </Form>
    );
}
