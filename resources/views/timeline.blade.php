@extends('layouts.app')

@section('title', 'Хронология эпох PC-игр - TimeLapse Games')

@section('content')
<div class="container py-5">
    <!-- Заголовок -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3 text-primary">🎮 Хронология эпох PC-игр</h1>
        <p class="lead text-muted">От мейнфреймов до облачного гейминга — путешествие по истории компьютерных игр</p>
    </div>

    <!-- Вертикальная шкала времени -->
    <div class="timeline-wrapper">
        @foreach($eras as $era)
        <div class="timeline-item position-relative mb-5">
            <!-- Точка на линии времени -->
            <div class="timeline-dot {{ $era['color'] }} shadow"></div>

            <!-- Карточка эпохи -->
            <div class="card shadow-lg border-0 timeline-card">
                <!-- Заголовок карточки -->
                <div class="card-header {{ $era['color'] }} text-white d-flex align-items-center py-3">
                    <div class="era-icon me-3">
                        <i class="{{ $era['icon'] }} fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="mb-1">{{ $era['name'] }}</h3>
                        <p class="mb-0 opacity-90">
                            <i class="fas fa-calendar-alt me-1"></i>{{ $era['years'] }}
                        </p>
                    </div>
                </div>

                <!-- Тело карточки -->
                <div class="card-body">
                    <div class="row">
                        <!-- Левая колонка: Описание и игры -->
                        <div class="col-lg-8">
                            <p class="fs-5 mb-4">{{ $era['description'] }}</p>

                            <!-- Ключевые игры -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-gamepad me-2"></i>Ключевые игры эпохи
                                </h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($era['key_games'] as $game)
                                    <span class="badge bg-light text-dark border p-2">
                                        <i class="fas fa-star text-warning me-1"></i>{{ $game }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Правая колонка: Платформы и информация -->
                        <div class="col-lg-4">
                            <!-- Платформы -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">
                                        <i class="fas fa-desktop me-1"></i> Основные платформы
                                    </h6>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($era['platforms'] as $platform)
                                        <li class="mb-2">
                                            <i class="fas fa-chevron-circle-right text-primary me-2"></i>
                                            <span class="text-dark">{{ $platform }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Завершение эпохи -->
                            <div class="p-3 bg-light border-start border-4 border-primary rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-flag-checkered me-1"></i> Конец эпохи
                                </h6>
                                <p class="mb-0 text-dark">{{ $era['end_reason'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Футер карточки -->
                <div class="card-footer bg-transparent border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge {{ $era['color'] }} fs-6 p-2 px-3">
                            <i class="fas fa-history me-1"></i>Эпоха #{{ $era['id'] }}
                        </span>
                        <!-- Исправленная кнопка с двумя состояниями -->
                        <button class="btn btn-sm btn-outline-primary era-toggle-btn"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#eraDetails{{ $era['id'] }}"
                                aria-expanded="false"
                                aria-controls="eraDetails{{ $era['id'] }}">
                            <span class="collapsed">
                                <i class="fas fa-info-circle me-1"></i> Детали эпохи
                            </span>
                            <span class="expanded">
                                <i class="fas fa-times-circle me-1"></i> Скрыть детали
                            </span>
                        </button>
                    </div>

                    <!-- Дополнительная информация (скрытая) -->
                    <div class="collapse mt-3" id="eraDetails{{ $era['id'] }}">
                        <div class="card card-body bg-light">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-clipboard-list me-1"></i>Характеристики эпохи
                            </h6>
                            <ul class="mb-0">
                                <li class="mb-2">
                                    <strong>Период:</strong> {{ $era['years'] }}
                                </li>
                                <li class="mb-2">
                                    <strong>Технологический фокус:</strong> {{ $era['description'] }}
                                </li>
                                <li>
                                    <strong>Переход к следующей эпохе:</strong> {{ $era['end_reason'] }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Легенда эпох -->
    <div class="card shadow-sm border-0 mt-5">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-key me-2"></i>Легенда эпох
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($eras as $era)
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex align-items-center p-3 border rounded">
                        <span class="badge {{ $era['color'] }} p-3 me-3"></span>
                        <div>
                            <strong class="d-block">{{ $era['name'] }}</strong>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>{{ $era['years'] }}
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Связь с играми -->
    <div class="text-center mt-5">
        <div class="card border-primary shadow">
            <div class="card-body py-4">
                <h4 class="card-title mb-3">
                    <i class="fas fa-link me-2"></i>Исследуйте игры каждой эпохи
                </h4>
                <p class="card-text text-muted mb-4">
                    В нашей базе данных собраны игры из разных исторических периодов.
                    Вы можете фильтровать их по году выпуска и жанру.
                </p>
                <a href="{{ route('games.index') }}" class="btn btn-primary btn-lg px-4">
                    <i class="fas fa-gamepad me-2"></i>Перейти ко всем играм
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg px-4 ms-2">
                    <i class="fas fa-home me-2"></i>На главную
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline-wrapper {
        position: relative;
        padding-left: 60px;
    }

    .timeline-wrapper::before {
        content: '';
        position: absolute;
        left: 25px;
        top: 0;
        bottom: 0;
        width: 5px;
        background: linear-gradient(to bottom, #6366f1 0%, #8b5cf6 25%, #ec4899 50%, #f59e0b 75%, #10b981 100%);
        border-radius: 3px;
    }

    .timeline-dot {
        position: absolute;
        left: 17px;
        top: 40px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        z-index: 2;
        transition: transform 0.3s;
    }

    .timeline-item:hover .timeline-dot {
        transform: scale(1.2);
    }

    .timeline-card {
        margin-left: 40px;
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }

    .timeline-card:hover {
        transform: translateX(10px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .era-icon {
        background: rgba(255, 255, 255, 0.2);
        padding: 10px;
        border-radius: 10px;
    }

    /* Стили для переключения текста кнопки */
    .era-toggle-btn[aria-expanded="false"] .expanded {
        display: none;
    }

    .era-toggle-btn[aria-expanded="true"] .collapsed {
        display: none;
    }

    .era-toggle-btn[aria-expanded="true"] .expanded {
        display: inline;
    }

    .era-toggle-btn[aria-expanded="false"] .collapsed {
        display: inline;
    }

    /* Анимация появления карточек */
    .timeline-item {
        opacity: 0;
        transform: translateX(-30px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .timeline-item.animated {
        opacity: 1;
        transform: translateX(0);
    }

    @media (max-width: 768px) {
        .timeline-wrapper {
            padding-left: 40px;
        }

        .timeline-wrapper::before {
            left: 15px;
        }

        .timeline-dot {
            left: 7px;
            width: 22px;
            height: 22px;
            border-width: 3px;
        }

        .timeline-card {
            margin-left: 25px;
        }
    }
</style>

<script>
    // Только анимация появления карточек при скролле
    document.addEventListener('DOMContentLoaded', function() {
        const timelineItems = document.querySelectorAll('.timeline-item');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        timelineItems.forEach(item => {
            observer.observe(item);
        });
    });
</script>
@endsection
