<?php

namespace Truelab\KottiMultilanguageBundle\Repository;
use Symfony\Cmf\Bundle\RoutingBundle\Tests\Resources\Document\Content;
use Truelab\KottiModelBundle\Model\ContentInterface;
use Truelab\KottiModelBundle\Repository\AliasRepository;
use Truelab\KottiModelBundle\Model\NodeInterface;

/**
 * Class TranslationRepository
 * @package Truelab\KottiMultilanguageBundle\Repository
 */
class TranslationRepository extends AliasRepository implements TranslationRepositoryInterface
{
    public function getTranslationsMap(ContentInterface $source)
    {
        $map = [];

        $qb = $this->connection
            ->createQueryBuilder();

        $sql = $qb->select('*')
            ->from('translations', 't')
            ->leftJoin('t', 'contents', 'c', 'c.id = t.target_id')
            ->where('t.source_id = ' . $source->getId())
            ->getSQL();


        $raw = $this->connection->executeQuery($sql)->fetchAll();

        if(!empty($raw)) {
            foreach($raw as $record) {
                $node = $this->findOne(null, array(
                    'nodes.id = ?' => $record['target_id']
                ));
                if($node && $node->getLanguage()) {
                    $map[$node->getLanguage()] = $node;
                }
            }
        }

        // sourceNode is not the source but probably a target
        if(empty($raw)) {

            $qb = $this->connection
                ->createQueryBuilder();
            $sql = $qb->select('*')
                ->from('translations', 't')
                ->leftJoin('t', 'contents', 'c', 'c.id = t.source_id')
                ->where('t.target_id = ' . $source->getId())
                ->getSQL();
            $raw = $this->connection->executeQuery($sql)->fetchAll();

            foreach($raw as $record) {
                $node = $this->findOne(null, array(
                    'nodes.id = ?' => $record['source_id']
                ));
                if($node && $node->getLanguage()) {
                    $map[$node->getLanguage()] = $node;
                }
            }
        }

        if($source->getLanguage()) {
            $map[$source->getLanguage()] = $source;
        }

        return $map;
    }

    /**
     * Given a translation target, this returns the translation source
     * or Null.
     *
     * @param ContentInterface $content
     *
     * @return ContentInterface|null
     */
    public function getSource(ContentInterface $content)
    {
        $qb = $this->connection
            ->createQueryBuilder();

        $sql = $qb->select('*')
            ->from('translations', 't')
            ->where('t.target_id = ' . $content->getId())
            ->getSQL();


        $raw = $this->connection->executeQuery($sql)->fetchAll();

        if(!empty($raw)) {
            $result = $raw[0];
        }else{
            return null;
        }

        $node = $this->findOne(null, array(
            'nodes.id = ?' => $result['source_id']
        ));

        if($node instanceof NodeInterface) {
            return $node;
        }else{
            return null;
        }
    }

    /**
     *
     *
     *
     *
     *
     *

    def get_source(content):
        """Given a translation target, this returns the translation source
        or None.

        If ``content`` is the source, this returns ``None``.
        """
        translation = DBSession.query(Translation).filter_by(
            target_id = content.id
        ).first()
        if translation is not None:
            return translation.source


    def get_translations(content):
        source = get_source(content)

        if source is None
            source = content

        query = DBSession.query(Translation, Content).filter(
            Translation.source_id == source.id,
            Content.id == Translation.target_id,
        )

        res = dict((content.language, content) for translation, content in query)
        res.pop(content.language, None)

        if source is not content:
            res[source.language] = source

        return res
     *
     *
     *
     *
     */
}
