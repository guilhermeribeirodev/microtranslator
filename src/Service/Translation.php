<?php
/**
 * @author Marco Troisi
 * @created 04.04.15
 */

namespace MicroTranslator\Service;

use MicroTranslator\Repository\Translation as TranslationRepository;
use MicroTranslator\Entity\Translation as TranslationEntity;
use Moss\Locale\Translator\Translator;


class Translation {

    /**
     * @var TranslationRepository
     */
    private $translationRepository;
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @param TranslationRepository $translationRepository
     * @param Translator $translator
     */
    public function __construct(
        TranslationRepository $translationRepository,
        Translator $translator
    ) {
        $this->translationRepository = $translationRepository;
        $this->translator = $translator;
    }

    /**
     * @param TranslationEntity $entity
     * @return array|bool
     */
    public function save(TranslationEntity $entity)
    {
        return $this->translationRepository->save($entity);
    }

    /**
     * @param $criteria
     * @param TranslationEntity $entity
     * @param $options
     * @return bool
     */
    public function update($criteria, TranslationEntity $entity, $options)
    {
        return $this->translationRepository->update($criteria, $entity, $options);
    }

    /**
     * @return array
     */
    public function getAvailableLocales()
    {
        $cursor = $this->translationRepository->distinct('locale');

        if (!$cursor) {
            return $cursor;
        }

        $result = [];

        foreach ($cursor['values'] as $res) {
            $result[] = $res;
        }

        return $result;
    }

    /**
     * @param $locale
     * @param string $word
     * @return array
     */
    public function show($locale, $word = "")
    {
        $search['locale'] = $locale;

        $result = [$word => $this->translator->trans($word)];

        return $result;
    }

    /**
     * @return array
     */
    public function showAll()
    {
        return $this->translator->dictionary()->getTranslations();
    }

    /**
     * @param $locale
     * @param string $word
     * @return int
     */
    public function count($locale = "", $word = "")
    {
        $search = [];

        if ($locale !== "") {
            $search['locale'] = $locale;
        }

        if ($word !== "") {
            $search['word'] = $word;
        }

        return $this->translationRepository->count($search);
    }
}
