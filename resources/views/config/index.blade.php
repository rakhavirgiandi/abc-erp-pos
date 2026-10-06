


@extends('companies.v1.layouts.guest.index')

@section('style')
<style>
    body {
        background-color: #fff;
    }

    #main-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .menu-box-link {
        width: 66.666667%;
        text-decoration: none;
        color: #fff;
    }

    .menu-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }

    .menu-item {
        width: 16.666667%;
        min-width: 150px;
    }

    .menu-box {
        width: 100%;
        aspect-ratio: 1 / 1;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: .75rem;

        transition:
            transform .25s cubic-bezier(.2, .8, .2, 1),
            box-shadow .25s ease;

        cursor: pointer;
    }

    .menu-box:hover {
        transform: translateY(-8px);
        /* box-shadow: 0 18px 35px rgba(0, 0, 0, .15); */
    }

    .menu-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

/* .menu-box:hover .menu-box-icon {
    color: #fff;
} */

.menu-box-text {
    text-align: center;
    color: #fff;
    font-weight: 400;
    font-family: Lexend, sans-serif;
    font-size: 18px;
}
</style>
@endsection

@section('content')
<div class="container">
    <div class=" w-100">
        <div class="row justify-content-center gy-4">
            <div class="col-xl-2 col-6">
                <a href="{{ url('config/database') }}" class="menu-box-link">
                    <div class="menu-box bg-primary">
                        <div class="menu-body">
                            <div class="menu-box-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="90px" viewBox="0 -960 960 960" width="90px"><path d="M480-160q-140.23 0-230.12-35.73Q160-231.46 160-287.69V-680q0-49.85 93.58-84.92Q347.15-800 480-800t226.42 35.08Q800-729.85 800-680v392.31q0 56.23-89.88 91.96Q620.23-160 480-160Zm0-447.31q84.95 0 172.59-24.11 87.64-24.12 109.15-53.99-21.41-30.74-108.24-55.74-86.83-25-173.5-25-86.18 0-174.17 24.03-87.98 24.04-109.27 53.63 20.52 31.8 107.32 56.49 86.81 24.69 176.12 24.69Zm-.15 206.18q41.23 0 81.97-4.33t77.73-12.71q36.99-8.37 69.41-20.7 32.42-12.34 57.19-27.41v-174.87q-25.43 15.59-57.6 27.92-32.17 12.33-69.49 20.7-37.32 8.38-77.65 12.79-40.33 4.41-81.56 4.41-42.77 0-83.98-4.8-41.2-4.79-78.06-13.16-36.86-8.38-68.27-20.33-31.41-11.94-55.69-27.53v174.87q23.77 15.07 55.35 27.02 31.59 11.95 68.45 20.58 36.86 8.63 77.81 13.09 40.95 4.46 84.39 4.46Zm.15 207.28q52.21 0 99.6-6.33 47.4-6.33 85.22-17.7 37.82-11.38 64.31-27.16 26.49-15.78 37.02-34.06v-153.18q-24.77 15.59-57.19 27.59t-69.41 20.37q-36.99 8.37-77.65 12.7-40.67 4.34-82.05 4.34-43.44 0-84.39-4.46-40.95-4.47-77.81-12.84-36.86-8.37-68.19-20.5t-55.61-27.2v153.41q10.38 18.9 36.75 34.33 26.37 15.44 64.27 26.73 37.9 11.3 85.34 17.63 47.43 6.33 99.79 6.33Z"/></svg>
                            </div>
                            <span class="menu-box-text">Konfigurasi Database</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-6">
                <a href="{{ url('config/activity-log') }}" class="menu-box-link">
                    <div class="menu-box bg-primary">
                        <div class="menu-body">
                            <div class="menu-box-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="90px" viewBox="0 -960 960 960" width="90px"><path d="M477.49-160q-125.08 0-216.14-83.78-91.07-83.78-100.94-208.68h34Q206-342.92 286.17-268.38q80.16 74.53 191.32 74.53 120.07 0 203.6-83.93 83.53-83.94 83.53-204.01 0-119.11-83.68-201.74-83.68-82.62-203.45-82.62-61.41 0-116.21 25.82-54.79 25.82-97.02 70.53h97.43v33.85H205.44v-156.61h33.84v99.43q46.54-49.95 108.32-78.41Q409.38-800 477.49-800q66.54 0 124.8 24.96 58.27 24.96 101.93 68.46 43.65 43.5 68.95 101.62 25.29 58.11 25.29 124.14 0 66.54-25.29 125.06-25.3 58.53-68.95 101.85-43.66 43.32-101.93 68.62Q544.03-160 477.49-160Zm133.18-167.64L464-473.49v-207.48h33.85v193.48L634.92-351.9l-24.25 24.26Z"/></svg>
                            </div>
                            <span class="menu-box-text">Log</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-6">
                <a href="#" class="menu-box-link">
                    <div class="menu-box bg-primary">
                        <div class="menu-body">
                            <div class="menu-box-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="90px" viewBox="0 -960 960 960" width="90px"><path d="M460-300h40v-220h-40v220Zm37.35-284q7.27-7.08 7.27-17.54 0-10.46-7.08-17.54-7.08-7.07-17.54-7.07-10.46 0-17.54 7.07-7.08 7.08-7.08 17.54 0 10.46 7.27 17.54 7.27 7.08 17.35 7.08 10.08 0 17.35-7.08Zm-17.22 464q-74.67 0-140.41-28.34-65.73-28.34-114.36-76.92-48.63-48.58-76.99-114.26Q120-405.19 120-479.87q0-74.67 28.34-140.41 28.34-65.73 76.92-114.36 48.58-48.63 114.26-76.99Q405.19-840 479.87-840q74.67 0 140.41 28.34 65.73 28.34 114.36 76.92 48.63 48.58 76.99 114.26Q840-554.81 840-480.13q0 74.67-28.34 140.41-28.34 65.73-76.92 114.36-48.58 48.63-114.26 76.99Q554.81-120 480.13-120Zm-.13-40q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
                            </div>
                            <span class="menu-box-text">About</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-6">
                <a href="{{ url()->previous() !== url()->current() && !preg_match('#/config(?:/|$)#', parse_url(url()->previous(), PHP_URL_PATH)) ? url()->previous() : url('/login')}}" class="menu-box-link">
                    <div class="menu-box bg-primary">
                        <div class="menu-body">
                            <div class="menu-box-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="90px" viewBox="0 -960 960 960" width="90px"><path d="M256-227.69 227.69-256l224-224-224-224L256-732.31l224 224 224-224L732.31-704l-224 224 224 224L704-227.69l-224-224-224 224Z"/></svg>
                            </div>
                            <span class="menu-box-text">Close</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection