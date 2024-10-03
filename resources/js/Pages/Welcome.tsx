import { Button } from "@/components/ui/button";
import { Head, Link } from "@inertiajs/react";
import { LockKeyhole } from "lucide-react";

interface Props {
    ssoUrl: string;
}

export default function Welcome({ ssoUrl }: Props) {
    return (
        <>
            <Head title="Welcome" />
            <div className="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
                <img
                    id="background"
                    className="absolute -left-20 top-0 max-w-[877px]"
                    src="https://laravel.com/assets/img/welcome/background.svg"
                />
                <div className="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                    <div className="bg-white/80 p-4 rounded-lg shadow-lg">
                        <a href={`${ssoUrl}`} target="_blank">
                            <Button>
                                <LockKeyhole className="size-4 mr-2" />
                                Login sso
                            </Button>
                        </a>
                    </div>
                </div>
            </div>
        </>
    );
}
