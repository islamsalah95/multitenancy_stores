import { useForm } from '@inertiajs/react';
import { Head } from '@inertiajs/react';
import { Button, Card, CardBody, CardFooter, CardHeader, Input, Label } from '@/Components/ui';

export default function Login() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('store.login'));
    };

    return (
        <>
            <Head title="Store Login" />
            <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
                <div className="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <Card>
                        <CardHeader>
                            <h2 className="text-2xl font-bold text-center">Store Login</h2>
                        </CardHeader>
                        <CardBody>
                            <form onSubmit={submit}>
                                <div className="mb-4">
                                    <Label htmlFor="email">Email</Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value={data.email}
                                        className="mt-1 block w-full"
                                        autoComplete="username"
                                        isFocused={true}
                                        onChange={(e) => setData('email', e.target.value)}
                                    />
                                    {errors.email && <div className="text-red-500 text-sm mt-1">{errors.email}</div>}
                                </div>

                                <div className="mb-4">
                                    <Label htmlFor="password">Password</Label>
                                    <Input
                                        id="password"
                                        type="password"
                                        name="password"
                                        value={data.password}
                                        className="mt-1 block w-full"
                                        autoComplete="current-password"
                                        onChange={(e) => setData('password', e.target.value)}
                                    />
                                    {errors.password && <div className="text-red-500 text-sm mt-1">{errors.password}</div>}
                                </div>

                                <div className="block mt-4">
                                    <label className="flex items-center">
                                        <input
                                            type="checkbox"
                                            name="remember"
                                            checked={data.remember}
                                            onChange={(e) => setData('remember', e.target.checked)}
                                            className="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        />
                                        <span className="ml-2 text-sm text-gray-600">Remember me</span>
                                    </label>
                                </div>

                                <div className="flex items-center justify-end mt-4">
                                    <Button className="ml-4" disabled={processing}>
                                        Log in
                                    </Button>
                                </div>
                            </form>
                        </CardBody>
                        <CardFooter>
                            <div className="text-center">
                                <p className="text-sm text-gray-600">
                                    Don't have a store?{' '}
                                    <a href={route('store.register')} className="text-indigo-600 hover:text-indigo-900">
                                        Register here
                                    </a>
                                </p>
                            </div>
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </>
    );
} 